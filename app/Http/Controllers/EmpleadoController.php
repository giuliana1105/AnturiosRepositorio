<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Bodega;
use App\Models\Cargo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Asegúrate de importar esto
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EmpleadoController extends Controller
{
     //Aqu[i es donde estoy dando permisos
    
     use AuthorizesRequests; 
     public function __construct()
 {
     
     $this->authorizeResource(Empleado::class, 'empleado'); // ✅ Debe coincidir con la ruta
 }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $empleados = Empleado::with('bodega', 'cargo')
            ->when($search, function ($query, $search) {
                return $query->where('nombreemp', 'like', "%{$search}%")
                    ->orWhere('nro_identificacion', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('empleado.index', compact('empleados'));
    }

    public function create()
    {
        $bodegas = Bodega::all();
        $cargos = Cargo::all();
        return view('empleado.create', compact('bodegas', 'cargos'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required',
            'idbodega' => 'required',
            'codigocargo' => 'required|exists:cargos,codigocargo',
        ]);

        try {
            DB::insert("INSERT INTO empleados (nro_identificacion, nombreemp, apellidoemp, email, nro_telefono, direccionemp, idbodega, tipo_identificacion, codigocargo, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())", [
                $request->nro_identificacion,
                $request->nombreemp,
                $request->apellidoemp,
                $validatedData['email'],
                $request->nro_telefono,
                $request->direccionemp,
                $validatedData['idbodega'],
                $request->tipo_identificacion,
                $validatedData['codigocargo']
            ]);
            return redirect()->route('empleado.index')->with('success', 'Empleado creado con éxito.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Extraer solo el mensaje exacto del trigger en PostgreSQL
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
        $cargos = Cargo::all();

        return view('empleado.edit', compact('empleado', 'bodegas', 'cargos'));
    }

    public function update(Request $request, $nro_identificacion)
    {
        // Validar los datos
        $validatedData = $request->validate([
            'email' => 'required',
            'nro_telefono' => 'required',
            'direccionemp' => 'required',
            'tipo_identificacion' => 'required|in:Cedula,RUC,Pasaporte',
            'nro_identificacion' => 'required',
            'codigocargo' => 'required|exists:cargos,codigocargo',
            'idbodega' => 'required|exists:bodegas,idbodega',
        ]);

        try {
            // Buscar el empleado y actualizar sus datos
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

            return redirect()->route('empleado.index')->with('success', 'Empleado actualizado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Capturar el mensaje de error del trigger
            $errorMessage = $e->getMessage();

            // Extraer solo el mensaje específico del error en PostgreSQL
            if (preg_match("/ERROR:  (.*?)\\n/", $errorMessage, $matches)) {
                $errorText = trim($matches[1]);  // Mensaje exacto del trigger
            } else {
                $errorText = 'Error al actualizar el empleado.';
            }

            return redirect()->back()->withInput()->with('error', $errorText);
        }
    }




    public function destroy($nro_identificacion)
    {
        Empleado::findOrFail($nro_identificacion)->delete();
        return redirect()->route('empleado.index')->with('success', 'Empleado eliminado exitosamente.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls'
        ]);

        $file = $request->file('excel_file');
        $rows = Excel::toArray([], $file)[0];

        $bodegas = Bodega::pluck('idbodega')->toArray();
        $cargos = Cargo::pluck('codigocargo')->toArray();

        $errores = [];
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Saltar encabezado

            $data = [
                'nro_identificacion' => $row[0] ?? null,
                'nombreemp' => $row[1] ?? null,
                'apellidoemp' => $row[2] ?? null,
                'email' => $row[3] ?? null,
                'nro_telefono' => $row[4] ?? null,
                'direccionemp' => $row[5] ?? null,
                'idbodega' => $row[6] ?? null,
                'tipo_identificacion' => $row[7] ?? null,
                'codigocargo' => $row[8] ?? null,
            ];

            // Validar bodega y cargo
            if (!in_array($data['idbodega'], $bodegas)) {
                $errores[] = "Fila " . ($index + 1) . ": La bodega '{$data['idbodega']}' no existe.";
                continue;
            }
            if (!in_array($data['codigocargo'], $cargos)) {
                $errores[] = "Fila " . ($index + 1) . ": El cargo '{$data['codigocargo']}' no existe.";
                continue;
            }

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

        return redirect()->route('empleado.index')->with('success', 'Empleados importados correctamente.');
    }
}
