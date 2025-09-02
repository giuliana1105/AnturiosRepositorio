<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Bodega;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EmpleadoController extends Controller
{
    use AuthorizesRequests; 
    public function __construct()
    {
        //$this->authorizeResource(Empleado::class, 'empleado');
    }

    // Cargos fijos
    private $cargos = [
        1 => 'Administrador',
        2 => 'Vendedor camión',
        3 => 'Vendedor',
        4 => 'Jefe de bodega',
        5 => 'Gerente',
    ];

    public function index(Request $request)
    {
        $cargo = auth()->user()->cargoNombre();
        if (in_array($cargo, ['Vendedor', 'Vendedor camión', 'Jefe de bodega'])) {
         abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $search = $request->input('search');
        $empleados = Empleado::with('bodega')
            ->when($search, function ($query, $search) {
                return $query->where('nombreemp', 'like', "%{$search}%")
                    ->orWhere('nro_identificacion', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
       $cargo = auth()->user()->cargoNombre();
        if (in_array($cargo, ['Vendedor', 'Vendedor camión', 'Jefe de bodega'])) {
         abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $bodegas = Bodega::all();
        $cargos = $this->cargos;
        return view('empleados.create', compact('bodegas', 'cargos'));
    }

    public function store(Request $request)
    {
       $cargo = auth()->user()->cargoNombre();
        if (in_array($cargo, ['Vendedor', 'Vendedor camión', 'Jefe de bodega'])) {
         abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        $validatedData = $request->validate([
            'email' => 'required',
            'idbodega' => 'required',
            'codigocargo' => 'required|in:1,2,3,4,5',
        ]);

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
       $cargo = auth()->user()->cargoNombre();
        if (in_array($cargo, ['Vendedor', 'Vendedor camión', 'Jefe de bodega'])) {
         abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $empleado = Empleado::findOrFail($nro_identificacion);
        $bodegas = Bodega::all();
        $cargos = $this->cargos;
        return view('empleados.edit', compact('empleado', 'bodegas', 'cargos'));
    }

    public function update(Request $request, $nro_identificacion)
    {
        $cargo = auth()->user()->cargoNombre();
        if (in_array($cargo, ['Vendedor', 'Vendedor camión', 'Jefe de bodega'])) {
         abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        $validatedData = $request->validate([
            'email' => 'required',
            'nro_telefono' => 'required',
            'direccionemp' => 'required',
            'tipo_identificacion' => 'required|in:Cedula,RUC,Pasaporte',
            'nro_identificacion' => 'required',
            'codigocargo' => 'required|in:1,2,3,4,5',
            'idbodega' => 'required|exists:bodegas,idbodega',
        ]);

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
       $cargo = auth()->user()->cargoNombre();
        if (in_array($cargo, ['Vendedor', 'Vendedor camión', 'Jefe de bodega'])) {
         abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        Empleado::findOrFail($nro_identificacion)->delete();
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado exitosamente.');
    }

    public function import(Request $request)
    {
         $cargo = auth()->user()->cargoNombre();
        if (in_array($cargo, ['Vendedor', 'Vendedor camión', 'Jefe de bodega'])) {
         abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');
        $rows = Excel::toArray([], $file)[0];

        $bodegas = Bodega::all()->keyBy(function($item) {
            return mb_strtolower(trim($item->nombrebodega));
        });

        // Cargos fijos por nombre (insensible a mayúsculas/minúsculas)
        $cargosPorNombre = [];
        foreach ($this->cargos as $codigo => $nombre) {
            $cargosPorNombre[mb_strtolower($nombre)] = $codigo;
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
                DB::insert("INSERT INTO empleados (nro_identificacion, nombreemp, apellidoemp, email, nro_telefono, direccionemp, idbodega, tipo_identificacion, codigocargo, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())", [
                    $data['nro_identificacion'],
                    $data['nombreemp'],
                    $data['apellidoemp'],
                    $data['email'],
                    $data['nro_telefono'],
                    $data['direccionemp'],
                    $data['idbodega'],
                    $data['tipo_identificacion'],
                    $data['codigocargo']
                ]);
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
         $cargo = auth()->user()->cargoNombre();
        if (in_array($cargo, ['Vendedor', 'Vendedor camión', 'Jefe de bodega'])) {
         abort(403, 'No tienes permiso para acceder a esta sección.');
        }
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
