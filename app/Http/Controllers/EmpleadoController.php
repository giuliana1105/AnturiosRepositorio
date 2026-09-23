<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Bodega;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Maatwebsite\Excel\Facades\Excel;

class EmpleadoController extends Controller
{
    use AuthorizesRequests; 
    public function __construct()
    {
        $this->authorizeResource(Empleado::class, 'empleado');
    }



    public function index(Request $request)
    {

        $search = $request->input('search');
        $empleados = Empleado::with('bodega')
            ->when($search, function ($query, $search) {
                return $query->where('nombreemp', 'like', "%{$search}%")
                    ->orWhere('nro_identificacion', 'like', "%{$search}%");
            })
            ->paginate(5);

        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {

        $bodegas = Bodega::all();
        $cargos = \Spatie\Permission\Models\Role::pluck('name', 'id')->toArray();
        return view('empleados.create', compact('bodegas', 'cargos'));
    }

    private function validarIdentificacion(Request $request)
    {
        $tipo = $request->input('tipo_identificacion');
        $num = trim($request->input('nro_identificacion', ''));

        if ($tipo === 'Cedula') {
            if (!preg_match('/^\d{10}$/', $num)) {
                return 'La cédula debe contener exactamente 10 dígitos numéricos, ni más ni menos.';
            }
        } elseif ($tipo === 'RUC') {
            if (!preg_match('/^\d{13}$/', $num) || !str_ends_with($num, '001')) {
                return 'El RUC debe contener exactamente 13 dígitos numéricos en total y terminar en 001.';
            }
        }
        return null;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nro_identificacion' => 'required',
            'nombreemp' => 'required',
            'apellidoemp' => 'required',
            'email' => 'required',
            'idbodega' => 'required',
            'tipo_identificacion' => 'required|in:Cedula,RUC,Pasaporte',
            'codigocargo' => 'required|exists:roles,id',
        ]);

        if ($errorId = $this->validarIdentificacion($request)) {
            return redirect()->back()->withInput()->with('error', $errorId)->withErrors(['nro_identificacion' => $errorId]);
        }

        try {
            Empleado::create([
                'nro_identificacion' => $request->nro_identificacion,
                'nombreemp' => $request->nombreemp,
                'apellidoemp' => $request->apellidoemp,
                'email' => $validatedData['email'],
                'nro_telefono' => $request->nro_telefono,
                'direccionemp' => $request->direccionemp,
                'idbodega' => $validatedData['idbodega'],
                'tipo_identificacion' => $request->tipo_identificacion,
                'codigocargo' => $validatedData['codigocargo'],
            ]);
            return redirect()->route('empleados.index')->with('success', 'Empleado creado con éxito.');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorMessage = $e->getMessage();
            if (preg_match("/ERROR:  (.*?)\\n/", $errorMessage, $matches)) {
                $errorText = trim($matches[1]);
            } else {
                $errorText = 'Error al crear el empleado.';
            }
            return redirect()->back()->withInput()->with('error', $errorText);
        }
    }

    public function edit($nro_identificacion)
    {

        $empleado = Empleado::findOrFail($nro_identificacion);
        $bodegas = Bodega::all();
        $cargos = \Spatie\Permission\Models\Role::pluck('name', 'id')->toArray();
        return view('empleados.edit', compact('empleado', 'bodegas', 'cargos'));
    }

    public function update(Request $request, $nro_identificacion)
    {
        $validatedData = $request->validate([
            'email' => 'required',
            'nro_telefono' => 'required',
            'direccionemp' => 'required',
            'tipo_identificacion' => 'required|in:Cedula,RUC,Pasaporte',
            'nro_identificacion' => 'required',
            'codigocargo' => 'required|exists:roles,id',
            'idbodega' => 'required|exists:bodegas,idbodega',
        ]);

        if ($errorId = $this->validarIdentificacion($request)) {
            return redirect()->back()->withInput()->with('error', $errorId)->withErrors(['nro_identificacion' => $errorId]);
        }

        try {
            $empleado = Empleado::findOrFail($nro_identificacion);
            $empleado->update([
                'nro_identificacion' => $request->nro_identificacion, 
                'nombreemp' => $request->nombreemp,
                'apellidoemp' => $request->apellidoemp,
                'email' => $validatedData['email'],
                'nro_telefono' => $validatedData['nro_telefono'],
                'direccionemp' => $validatedData['direccionemp'],
                'tipo_identificacion' => $validatedData['tipo_identificacion'],
                'codigocargo' => $validatedData['codigocargo'],
                'idbodega' => $validatedData['idbodega'],
            ]);
            return redirect()->route('empleados.index')->with('success', 'Empleado actualizado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorMessage = $e->getMessage();
            if (preg_match("/ERROR:  (.*?)\\n/", $errorMessage, $matches)) {
                $errorText = trim($matches[1]);
            } else {
                $errorText = 'Error al actualizar el empleado.';
            }
            return redirect()->back()->withInput()->with('error', $errorText);
        }
    }

    public function destroy($nro_identificacion)
    {
        Empleado::findOrFail($nro_identificacion)->delete();
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado exitosamente.');
    }

    public function import(Request $request)
    {
        $this->authorize('create', Empleado::class);

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');
        $rows = Excel::toArray([], $file)[0];

        $bodegas = Bodega::all()->keyBy(function($item) {
            return mb_strtolower(trim($item->nombrebodega));
        });

        // Cargos dinámicos desde Spatie (insensible a mayúsculas/minúsculas)
        $cargosPorNombre = [];
        $roles = \Spatie\Permission\Models\Role::all();
        foreach ($roles as $role) {
            $cargosPorNombre[mb_strtolower($role->name)] = $role->id;
        }

        $errores = [];
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Saltar encabezado

            $nombreBodegaExcel = mb_strtolower(trim($row[6] ?? ''));
            $bodega = Bodega::whereRaw('LOWER(nombrebodega) = ?', [$nombreBodegaExcel])->first();
            if (!$bodega) {
                $errores[] = "Fila " . ($index + 1) . ": La bodega '{$row[6]}' no existe.";
                continue;
            }

            $nombreCargoExcel = mb_strtolower(trim($row[8] ?? ''));
            $codigocargo = $cargosPorNombre[$nombreCargoExcel] ?? null;
            if (!$codigocargo) {
                $errores[] = "Fila " . ($index + 1) . ": El cargo '{$row[8]}' no es válido.";
                continue;
            }

            $tipoIdentificacionExcel = mb_strtoupper(trim($row[7] ?? ''));
            $tipoIdentificacionExcel = str_replace(['Á','É','Í','Ó','Ú','á','é','í','ó','ú'], ['A','E','I','O','U','A','E','I','O','U'], $tipoIdentificacionExcel);

            if ($tipoIdentificacionExcel === 'CEDULA') {
                $tipoIdentificacionFinal = 'Cedula';
            } elseif ($tipoIdentificacionExcel === 'RUC') {
                $tipoIdentificacionFinal = 'RUC';
            } elseif ($tipoIdentificacionExcel === 'PASAPORTE') {
                $tipoIdentificacionFinal = 'Pasaporte';
            } else {
                $errores[] = "Fila " . ($index + 1) . ": El tipo de identificación '{$row[7]}' no es válido. Debe ser Cedula, RUC o Pasaporte.";
                continue;
            }

            $data = [
                'nro_identificacion' => $row[0] ?? null,
                'nombreemp' => $row[1] ?? null,
                'apellidoemp' => $row[2] ?? null,
                'email' => $row[3] ?? null,
                'nro_telefono' => $row[4] ?? null,
                'direccionemp' => $row[5] ?? null,
                'idbodega' => $bodega->idbodega,
                'tipo_identificacion' => $tipoIdentificacionFinal,
                'codigocargo' => $codigocargo,
            ];

            try {
                Empleado::create($data);
            } catch (\Exception $e) {
                $errores[] = "Fila " . ($index + 1) . ": " . $e->getMessage();
            }
        }

        if ($errores) {
            return redirect()->back()->with('error', implode('<br>', $errores));
        }

        return redirect()->route('empleados.index')->with('success', 'Empleados importados correctamente.');
    }

    public function resetPassword($nro_identificacion)
    {
        $this->authorize('update', Empleado::class);
        $empleado = \App\Models\Empleado::findOrFail($nro_identificacion);
        $user = \App\Models\User::where('email', $empleado->email)->first();

        if ($user) {
            $user->password = $empleado->nro_identificacion; // Se usará el mutator para encriptar
            $user->must_change_password = true; // Obliga a cambiar la contraseña al ingresar
            $user->save();
            return back()->with('success', 'La contraseña fue restablecida al número de cédula. El usuario deberá cambiarla al ingresar.');
        } else {
            return back()->with('error', 'No se encontró usuario asociado a este empleado.');
        }
    }
}
