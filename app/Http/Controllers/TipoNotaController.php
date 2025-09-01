<?php

namespace App\Http\Controllers;

use App\Models\TipoNota;
use App\Models\Empleado;
use App\Models\Bodega;
use App\Models\Producto;
use App\Models\DetalleTipoNota;
use Barryvdh\DomPDF\Facade\Pdf; // ✅ Importación corregida
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Iluminate\Support\Facades\Log; // Asegúrate de importar esto si lo necesitas

//use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Asegúrate de importar esto

class TipoNotaController extends Controller
{
    //     use AuthorizesRequests;
    //     public function __construct()
    // {

    //     $this->authorizeResource(TipoNota::class, 'tipoNota'); // ✅ Debe coincidir con la ruta
    // }


    /**
     * Muestra la lista de notas.
     */
    // public function index()
    // {
    //     $tipoNotas = TipoNota::with(['responsableEmpleado', 'bodega', 'detalles.producto', 'transaccion'])
    //         ->paginate(10);

    //     return view('tipoNota.index', compact('tipoNotas'));
    // }
// public function index()
// {
//     $tipoNotas = TipoNota::with([
//         'responsableEmpleado',
//         'bodega',
//         'transaccion'
//     ])
//     ->orderBy('fechanota', 'desc')
//     ->paginate(10);

//     // Carga manual de productos para cada detalle
//     $tipoNotas->each(function($nota) {
//         $nota->load(['detalles' => function($query) {
//             $query->with(['producto' => function($q) {
//                 $q->select('codigo', 'nombre', 'tipoempaque');
//             }]);
//         }]);
//     });

//     return view('tipoNota.index', compact('tipoNotas'));
// }



// Reemplaza el método index() en tu TipoNotaController.php

// public function index()
// {
//     $tipoNotas = TipoNota::with([
//         'responsableEmpleado',
//         'bodega',
//         'transaccion',
//         'detalles' => function($query) {
//             $query->with('producto');
//         }
//     ])
//     ->orderBy('fechanota', 'desc')
//     ->paginate(10);

//     // Verificar que los productos se carguen correctamente
//     $tipoNotas->each(function($nota) {
//         $nota->detalles->each(function($detalle) {
//             // Si no se cargó el producto por la relación, lo buscamos manualmente
//             if (!$detalle->producto) {
//                 $detalle->producto = \App\Models\Producto::where('codigo', $detalle->codigoproducto)->first();
//             }
//         });
//     });

//     return view('tipoNota.index', compact('tipoNotas'));
// }

public function index(Request $request)
{
    $bodegas = Bodega::all();

    $query = TipoNota::with([
        'responsableEmpleado',
        'bodega',
        'transaccion',
        'detalles' => function($query) {
            $query->with('producto');
        }
    ]);

    // Filtro por bodega
    if ($request->filled('bodega')) {
        $query->where('idbodega', $request->bodega);
    }

    // Filtro por tipo
    if ($request->filled('tipo')) {
        $query->where('tiponota', $request->tipo);
    }

    $tipoNotas = $query
        ->orderBy('created_at', 'desc')
        ->orderBy('codigo', 'desc')
        ->paginate(10)
        ->appends($request->all());

    // Carga productos en detalles
    $tipoNotas->each(function($nota) {
        $nota->detalles->each(function($detalle) {
            if (!$detalle->producto) {
                $detalle->producto = \App\Models\Producto::where('codigo', $detalle->codigoproducto)->first();
            }
        });
    });

    return view('tipoNota.index', [
        'tipoNotas' => $tipoNotas,
        'bodegas' => $bodegas,
    ]);
}


    /**
     * Muestra el formulario para crear una nueva nota.
     */
    public function create()
    {
        $empleados = Empleado::all();
        $bodegas = Bodega::all();
        $productos = Producto::all();

        return view('tipoNota.create', compact('empleados', 'bodegas', 'productos'));
    }

    /**
     * Guarda una nueva nota en la base de datos.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'tiponota' => 'required|string|max:255',
    //         'nro_identificacion' => 'required|exists:empleados,nro_identificacion',
    //         'idbodega' => 'required|string|exists:bodegas,idbodega',
    //         'codigoproducto' => 'required|array|min:1',
    //         'cantidad' => 'required|array|min:1',
    //     ]);

    //     // Validación para devoluciones: no permitir devolver más de lo que hay en la bodega
    //     if ($request->tiponota === 'DEVOLUCION') {
    //         foreach ($request->codigoproducto as $index => $codigo) {
    //             $stock = DB::table('productos_bodega')
    //                 ->where('bodega_id', $request->idbodega)
    //                 ->where('producto_id', $codigo)
    //                 ->selectRaw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as stock')
    //                 ->value('stock') ?? 0;

    //             if ($request->cantidad[$index] > $stock) {
    //                 return redirect()->back()->with('error', 'Cantidad insuficiente para el producto ' . $codigo);
    //             }
    //         }
    //     }

    //     try {
    //         DB::beginTransaction();

    //         $ultimoCodigo = TipoNota::latest('codigo')->first();
    //         $numero = $ultimoCodigo ? intval(str_replace('TN-', '', $ultimoCodigo->codigo)) + 1 : 1;
    //         $codigoGenerado = 'TN-' . $numero;

    //         $nota = TipoNota::create([
    //             'codigo' => $codigoGenerado,
    //             'tiponota' => $request->tiponota,
    //             'nro_identificacion' => $request->nro_identificacion,
    //             'idbodega' => $request->idbodega,
    //             'fechanota' => now(),
    //         ]);

    //         foreach ($request->codigoproducto as $index => $codigo) {
    //             // Guarda el detalle de la nota
    //             DetalleTipoNota::create([
    //                 'tipo_nota_id' => $nota->codigo,
    //                 'codigoproducto' => $codigo,
    //                 'cantidad' => $request->cantidad[$index],
    //             ]);

    //             // Guarda el movimiento en productos_bodega
    //             DB::table('productos_bodega')->insert([
    //                 'bodega_id' => $request->idbodega,
    //                 'producto_id' => $codigo, // <-- Debe ser el código del producto, ej: 'PF003'
    //                 'cantidad' => $request->cantidad[$index],
    //                 'fecha' => now(),
    //                 'es_devolucion' => $request->tiponota === 'DEVOLUCION',
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);
    //         }

    //         DB::commit();
    //         return redirect()->route('tipoNota.index')->with('success', 'Nota creada exitosamente.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Error al crear la nota: ' . $e->getMessage());
    //     }
    // }


//     public function store(Request $request)
// {
//     $request->validate([
//         'tiponota' => 'required|string|max:255',
//         'nro_identificacion' => 'required|exists:empleados,nro_identificacion',
//         'idbodega' => 'required|string|exists:bodegas,idbodega',
//         'codigoproducto' => 'required|array|min:1',
//         'cantidad' => 'required|array|min:1',
//     ]);

//     // Validación para devoluciones
//     if ($request->tiponota === 'DEVOLUCION') {
//         foreach ($request->codigoproducto as $index => $codigo) {
//             $stock = DB::table('productos_bodega')
//                 ->where('bodega_id', $request->idbodega)
//                 ->where('producto_id', $codigo)
//                 ->selectRaw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as stock')
//                 ->value('stock') ?? 0;

//             if ($request->cantidad[$index] > $stock) {
//                 return redirect()->back()->with('error', 'Cantidad insuficiente para el producto ' . $codigo);
//             }
//         }
//     }

//     try {
//         DB::beginTransaction();

//         // 🔥 Solución mejorada: Bloquear la tabla para evitar duplicados
//         $ultimoCodigo = TipoNota::lockForUpdate()->orderBy('codigo', 'desc')->first();
//         $numero = $ultimoCodigo ? intval(str_replace('TN-', '', $ultimoCodigo->codigo)) + 1 : 1;
//         $codigoGenerado = 'TN-' . $numero;

//         $nota = TipoNota::create([
//             'codigo' => $codigoGenerado, // Código único generado
//             'tiponota' => $request->tiponota,
//             'nro_identificacion' => $request->nro_identificacion,
//             'idbodega' => $request->idbodega,
//             'fechanota' => now(),
//         ]);

//         foreach ($request->codigoproducto as $index => $codigo) {
//             DetalleTipoNota::create([
//                 'tipo_nota_id' => $nota->codigo,
//                 'codigoproducto' => $codigo,
//                 'cantidad' => $request->cantidad[$index],
//             ]);

//             DB::table('productos_bodega')->insert([
//                 'bodega_id' => $request->idbodega,
//                 'producto_id' => $codigo,
//                 'cantidad' => $request->cantidad[$index],
//                 'fecha' => now(),
//                 'es_devolucion' => $request->tiponota === 'DEVOLUCION',
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);
//         }

//         DB::commit();
//         return redirect()->route('tipoNota.index')->with('success', 'Nota creada exitosamente.');
//     } catch (\Exception $e) {
//         DB::rollBack();
//         return redirect()->back()->with('error', 'Error al crear la nota: ' . $e->getMessage());
//     }
// }



//met
// public function store(Request $request)
// {
//     $request->validate([
//         'tiponota' => 'required|string|max:255',
//         'nro_identificacion' => 'required|exists:empleados,nro_identificacion',
//         'idbodega' => 'required|string|exists:bodegas,idbodega',
//         'codigoproducto' => 'required|array|min:1',
//         'cantidad' => 'required|array|min:1',
//     ]);

//     // Validación para devoluciones
//     if ($request->tiponota === 'DEVOLUCION') {
//         foreach ($request->codigoproducto as $index => $codigo) {
//             $stock = DB::table('productos_bodega')
//                 ->where('bodega_id', $request->idbodega)
//                 ->where('producto_id', $codigo)
//                 ->selectRaw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as stock')
//                 ->value('stock') ?? 0;

//             if ($request->cantidad[$index] > $stock) {
//                 return redirect()->back()->with('error', 'Cantidad insuficiente para el producto ' . $codigo);
//             }
//         }
//     }

//     $maxAttempts = 5;
//     $attempt = 0;

//     while ($attempt < $maxAttempts) {
//         try {
//             DB::beginTransaction();

//             // SOLUCIÓN MEJORADA: Usar una consulta atómica para generar el código
//             $nuevoCodigo = DB::transaction(function () {
//                 $ultimoCodigo = TipoNota::lockForUpdate()
//                     ->orderByRaw("SUBSTRING(codigo FROM 4)::int DESC")
//                     ->first();
                
//                 $ultimoNumero = $ultimoCodigo ? (int) str_replace('TN-', '', $ultimoCodigo->codigo) : 0;
//                 return 'TN-' . ($ultimoNumero + 1);
//             });

//             $nota = TipoNota::create([
//                 'codigo' => $nuevoCodigo,
//                 'tiponota' => $request->tiponota,
//                 'nro_identificacion' => $request->nro_identificacion,
//                 'idbodega' => $request->idbodega,
//                 'fechanota' => now(),
//             ]);

//             // Resto de tu lógica para detalles y productos_bodega...

//             DB::commit();
//             return redirect()->route('tipoNota.index')->with('success', 'Nota creada exitosamente.');

//         } catch (\Illuminate\Database\QueryException $e) {
//             DB::rollBack();
            
//             if ($e->errorInfo[0] == '23505') { // Error de violación de unicidad
//                 $attempt++;
//                 if ($attempt >= $maxAttempts) {
//                     return redirect()->back()->with('error', 'No se pudo generar un código único después de varios intentos. Por favor intente nuevamente.');
//                 }
//                 continue;
//             }
//             return redirect()->back()->with('error', 'Error al crear la nota: ' . $e->getMessage());
//         } catch (\Exception $e) {
//             DB::rollBack();
//             return redirect()->back()->with('error', 'Error al crear la nota: ' . $e->getMessage());
//         }
//     }
// }


// public function store(Request $request)
// {
//     $request->validate([
//         'tiponota' => 'required|string|max:255',
//         'nro_identificacion' => 'required|exists:empleados,nro_identificacion',
//         'idbodega' => 'required|string|exists:bodegas,idbodega',
//         'codigoproducto' => 'required|array|min:1',
//         'cantidad' => 'required|array|min:1',
//     ]);

//     // Validación para devoluciones
//     if ($request->tiponota === 'DEVOLUCION') {
//         foreach ($request->codigoproducto as $index => $codigo) {
//             $stock = DB::table('productos_bodega')
//                 ->where('bodega_id', $request->idbodega)
//                 ->where('producto_id', $codigo)
//                 ->selectRaw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as stock')
//                 ->value('stock') ?? 0;

//             if ($request->cantidad[$index] > $stock) {
//                 return redirect()->back()->with('error', 'Cantidad insuficiente para el producto ' . $codigo);
//             }
//         }
//     }

//     $maxAttempts = 5;
//     $attempt = 0;

//     while ($attempt < $maxAttempts) {
//         try {
//             DB::beginTransaction();

//             // Generar código único
//             $nuevoCodigo = DB::transaction(function () {
//                 $ultimoCodigo = TipoNota::lockForUpdate()
//                     ->orderByRaw("CAST(SUBSTRING(codigo FROM 4) AS INTEGER) DESC")
//                     ->first();
                
//                 $ultimoNumero = $ultimoCodigo ? (int) str_replace('TN-', '', $ultimoCodigo->codigo) : 0;
//                 return 'TN-' . ($ultimoNumero + 1);
//             });

//             // Crear la nota
//             $nota = TipoNota::create([
//                 'codigo' => $nuevoCodigo,
//                 'tiponota' => $request->tiponota,
//                 'nro_identificacion' => $request->nro_identificacion,
//                 'idbodega' => $request->idbodega,
//                 'fechanota' => now(),
//             ]);

//             // Crear los detalles de la nota
//             foreach ($request->codigoproducto as $index => $codigo) {
//                 // Verificar que el producto existe
//                 $producto = \App\Models\Producto::where('codigo', $codigo)->first();
//                 if (!$producto) {
//                     throw new \Exception("El producto con código {$codigo} no existe.");
//                 }

//                 // Crear detalle
//                 DetalleTipoNota::create([
//                     'tipo_nota_id' => $nota->codigo,
//                     'codigoproducto' => $codigo,
//                     'cantidad' => $request->cantidad[$index],
//                 ]);

//                 // Registrar movimiento en productos_bodega
//                 DB::table('productos_bodega')->insert([
//                     'bodega_id' => $request->idbodega,
//                     'producto_id' => $codigo,
//                     'cantidad' => $request->cantidad[$index],
//                     'fecha' => now(),
//                     'es_devolucion' => $request->tiponota === 'DEVOLUCION',
//                     'created_at' => now(),
//                     'updated_at' => now(),
//                 ]);
//             }

//             DB::commit();
//             return redirect()->route('tipoNota.index')->with('success', 'Nota creada exitosamente.');

//         } catch (\Illuminate\Database\QueryException $e) {
//             DB::rollBack();
            
//             // Error de violación de unicidad
//             if (str_contains($e->getMessage(), 'duplicate key') || str_contains($e->getMessage(), 'UNIQUE constraint')) {
//                 $attempt++;
//                 if ($attempt >= $maxAttempts) {
//                     return redirect()->back()->with('error', 'No se pudo generar un código único después de varios intentos. Por favor intente nuevamente.');
//                 }
//                 continue;
//             }
//             return redirect()->back()->with('error', 'Error al crear la nota: ' . $e->getMessage());
//         } catch (\Exception $e) {
//             DB::rollBack();
//             return redirect()->back()->with('error', 'Error al crear la nota: ' . $e->getMessage());
//         }
//     }

//     return redirect()->back()->with('error', 'No se pudo crear la nota después de varios intentos.');
// }



// public function store(Request $request)
// {
//     $request->validate([
//         'tiponota' => 'required|string|max:255',
//         'nro_identificacion' => 'required|exists:empleados,nro_identificacion',
//         'idbodega' => 'required|string|exists:bodegas,idbodega',
//         'codigoproducto' => 'required|array|min:1',
//         'cantidad' => 'required|array|min:1',
//     ]);

//     // Validación para devoluciones
//     if ($request->tiponota === 'DEVOLUCION') {
//         foreach ($request->codigoproducto as $index => $codigo) {
//             $stock = DB::table('productos_bodega')
//                 ->where('bodega_id', $request->idbodega)
//                 ->where('producto_id', $codigo)
//                 ->selectRaw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as stock')
//                 ->value('stock') ?? 0;

//             if ($request->cantidad[$index] > $stock) {
//                 return redirect()->back()->with('error', 'Cantidad insuficiente para el producto ' . $codigo . ' en la bodega seleccionada.');
//             }
//         }
//     }

//     // Validación para envíos (verificar stock en tabla productos - bodega MASTER conceptual)
//     if ($request->tiponota === 'ENVIO') {
//         foreach ($request->codigoproducto as $index => $codigo) {
//             $producto = \App\Models\Producto::where('codigo', $codigo)->first();
//             if (!$producto) {
//                 return redirect()->back()->with('error', 'El producto con código ' . $codigo . ' no existe.');
//             }

//             $stockDisponible = $producto->cantidad ?? 0;

//             if ($request->cantidad[$index] > $stockDisponible) {
//                 return redirect()->back()->with('error', 'Cantidad insuficiente para el producto ' . $codigo . '. Stock disponible: ' . $stockDisponible);
//             }
//         }
//     }

//     try {
//         // Usar una sola transacción sin transacciones anidadas
//         DB::beginTransaction();

//         // Generar código único - usar created_at en lugar de id
//         $ultimoCodigo = TipoNota::orderBy('created_at', 'desc')->first();
        
//         $ultimoNumero = 0;
//         if ($ultimoCodigo && $ultimoCodigo->codigo) {
//             // Extraer el número del código TN-XXXX
//             $numeroExtraido = str_replace('TN-', '', $ultimoCodigo->codigo);
//             $ultimoNumero = is_numeric($numeroExtraido) ? (int) $numeroExtraido : 0;
//         }
        
//         $nuevoCodigo = 'TN-' . ($ultimoNumero + 1);

//         // Verificar que el código no exista (prevención adicional)
//         $existeCodigo = TipoNota::where('codigo', $nuevoCodigo)->exists();
//         if ($existeCodigo) {
//             // Si existe, buscar el siguiente disponible
//             do {
//                 $ultimoNumero++;
//                 $nuevoCodigo = 'TN-' . $ultimoNumero;
//             } while (TipoNota::where('codigo', $nuevoCodigo)->exists());
//         }

//         // Crear la nota UNA sola vez
//         $nota = TipoNota::create([
//             'codigo' => $nuevoCodigo,
//             'tiponota' => $request->tiponota,
//             'nro_identificacion' => $request->nro_identificacion,
//             'idbodega' => $request->idbodega,
//             'fechanota' => now(),
//         ]);

//         // Procesar cada producto UNA sola vez
//         foreach ($request->codigoproducto as $index => $codigo) {
//             $producto = \App\Models\Producto::where('codigo', $codigo)->first();
//             if (!$producto) {
//                 throw new \Exception("El producto con código {$codigo} no existe.");
//             }

//             $cantidad = $request->cantidad[$index];

//             // Crear detalle de la nota UNA vez
//             DetalleTipoNota::create([
//                 'tipo_nota_id' => $nota->codigo,
//                 'codigoproducto' => $codigo,
//                 'cantidad' => $cantidad,
//             ]);

//             // Realizar movimientos de inventario UNA vez según el tipo de nota
//             if ($request->tiponota === 'ENVIO') {
//                 // ENVÍO: De bodega MASTER a bodega específica
                
//                 // 1. Restar del stock general (tabla productos = bodega MASTER)
//                 DB::table('productos')
//                     ->where('codigo', $codigo)
//                     ->decrement('cantidad', $cantidad);

//                 // 2. Registrar entrada en bodega destino (UNA sola vez)
//                 DB::table('productos_bodega')->insert([
//                     'bodega_id' => $request->idbodega,
//                     'producto_id' => $codigo,
//                     'cantidad' => $cantidad,
//                     'fecha' => now(),
//                     'es_devolucion' => false,
//                     'created_at' => now(),
//                     'updated_at' => now(),
//                 ]);
                
//             } elseif ($request->tiponota === 'DEVOLUCION') {
//                 // DEVOLUCIÓN: De bodega específica a bodega MASTER
                
//                 // 1. Registrar salida de bodega origen (UNA sola vez)
//                 DB::table('productos_bodega')->insert([
//                     'bodega_id' => $request->idbodega,
//                     'producto_id' => $codigo,
//                     'cantidad' => $cantidad,
//                     'fecha' => now(),
//                     'es_devolucion' => true,
//                     'created_at' => now(),
//                     'updated_at' => now(),
//                 ]);

//                 // 2. Sumar al stock general (tabla productos = bodega MASTER)
//                 DB::table('productos')
//                     ->where('codigo', $codigo)
//                     ->increment('cantidad', $cantidad);
//             }
//         }

//         DB::commit();
//         return redirect()->route('tipoNota.index')->with('success', 'Nota creada exitosamente.');

//     } catch (\Exception $e) {
//         DB::rollBack();
//         \Log::error('Error al crear nota: ' . $e->getMessage(), [
//             'request_data' => $request->all(),
//             'stack_trace' => $e->getTraceAsString()
//         ]);
//         return redirect()->back()->with('error', 'Error al crear la nota: ' . $e->getMessage());
//     }
// }



public function store(Request $request)
{
    $request->validate([
        'tiponota' => 'required|string|max:255',
        'nro_identificacion' => 'required|exists:empleados,nro_identificacion',
        'idbodega' => 'required|string|exists:bodegas,idbodega',
        'codigoproducto' => 'required|array|min:1',
        'cantidad' => 'required|array|min:1',
    ]);

    // Validación para devoluciones
    if ($request->tiponota === 'DEVOLUCION') {
        foreach ($request->codigoproducto as $index => $codigo) {
            $stock = DB::table('productos_bodega')
                ->where('bodega_id', $request->idbodega)
                ->where('producto_id', $codigo)
                ->selectRaw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as stock')
                ->value('stock') ?? 0;

            if ($request->cantidad[$index] > $stock) {
                return redirect()->back()->with('error', 'Cantidad insuficiente para el producto ' . $codigo . ' en la bodega seleccionada.');
            }
        }
    }

    // Validación para envíos (verificar stock en tabla productos - bodega MASTER conceptual)
    if ($request->tiponota === 'ENVIO') {
        foreach ($request->codigoproducto as $index => $codigo) {
            $producto = \App\Models\Producto::where('codigo', $codigo)->first();
            if (!$producto) {
                return redirect()->back()->with('error', 'El producto con código ' . $codigo . ' no existe.');
            }

            $stockDisponible = $producto->cantidad ?? 0;

            if ($request->cantidad[$index] > $stockDisponible) {
                return redirect()->back()->with('error', 'Cantidad insuficiente para el producto ' . $codigo . '. Stock disponible: ' . $stockDisponible);
            }
        }
    }

    try {
        // Usar una sola transacción
        DB::beginTransaction();

        // Generar código único
        $ultimoCodigo = TipoNota::orderBy('created_at', 'desc')->first();
        
        $ultimoNumero = 0;
        if ($ultimoCodigo && $ultimoCodigo->codigo) {
            $numeroExtraido = str_replace('TN-', '', $ultimoCodigo->codigo);
            $ultimoNumero = is_numeric($numeroExtraido) ? (int) $numeroExtraido : 0;
        }
        
        $nuevoCodigo = 'TN-' . ($ultimoNumero + 1);

        // Verificar que el código no exista
        $existeCodigo = TipoNota::where('codigo', $nuevoCodigo)->exists();
        if ($existeCodigo) {
            do {
                $ultimoNumero++;
                $nuevoCodigo = 'TN-' . $ultimoNumero;
            } while (TipoNota::where('codigo', $nuevoCodigo)->exists());
        }

        // Crear la nota
        $nota = TipoNota::create([
            'codigo' => $nuevoCodigo,
            'tiponota' => $request->tiponota,
            'nro_identificacion' => $request->nro_identificacion,
            'idbodega' => $request->idbodega,
            'fechanota' => now(),
        ]);

        // SOLO crear los detalles de la nota - NO hacer movimientos de inventario aquí
        foreach ($request->codigoproducto as $index => $codigo) {
            $producto = \App\Models\Producto::where('codigo', $codigo)->first();
            if (!$producto) {
                throw new \Exception("El producto con código {$codigo} no existe.");
            }

            $cantidad = $request->cantidad[$index];

            // SOLO crear detalle de la nota
            DetalleTipoNota::create([
                'tipo_nota_id' => $nota->codigo,
                'codigoproducto' => $codigo,
                'cantidad' => $cantidad,
            ]);
        }

        DB::commit();
        return redirect()->route('tipoNota.index')->with('success', 'Nota creada exitosamente. Debe confirmarla para procesar el inventario.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al crear nota: ' . $e->getMessage(), [
            'request_data' => $request->all(),
            'stack_trace' => $e->getTraceAsString()
        ]);
        return redirect()->back()->with('error', 'Error al crear la nota: ' . $e->getMessage());
    }
}
public function debug($codigo)
{
    $nota = TipoNota::where('codigo', $codigo)->first();
    
    if (!$nota) {
        dd('Nota no encontrada');
    }
    
    $detalles = DetalleTipoNota::where('tipo_nota_id', $codigo)->get();
    
    $debug = [
        'nota' => $nota,
        'detalles_count' => $detalles->count(),
        'detalles' => $detalles,
        'productos_info' => []
    ];
    
    foreach ($detalles as $detalle) {
        $producto = \App\Models\Producto::where('codigo', $detalle->codigoproducto)->first();
        $debug['productos_info'][] = [
            'detalle' => $detalle,
            'producto_encontrado' => $producto ? 'SÍ' : 'NO',
            'producto' => $producto
        ];
    }
    
    dd($debug);
}
    /**
     * Muestra una nota específica.
     */
    public function show($codigo)
    {
        $tipoNota = TipoNota::with(['responsableEmpleado', 'bodega', 'detalles.producto'])
            ->where('codigo', $codigo)
            ->firstOrFail();

        return view('tipoNota.show', compact('tipoNota'));
    }

    /**
     * Muestra el formulario para editar una nota.
     */
    // public function edit($codigo)
    // {
    //     $tipoNota = TipoNota::with('detalles')->where('codigo', $codigo)->firstOrFail();
    //     $empleados = Empleado::all();
    //     $bodegas = Bodega::all();
    //     $productos = Producto::all();

    //     return view('tipoNota.edit', compact('tipoNota', 'empleados', 'bodegas', 'productos'));
    // }

    // /**
    //  * Actualiza una nota en la base de datos.
    //  */
    // public function update(Request $request, $codigo)
    // {
    //     $request->validate([
    //         'tiponota' => 'required|string|max:255',
    //         'nro_identificacion' => 'required|exists:empleados,nro_identificacion',
    //         'idbodega' => 'required|string|exists:bodegas,idbodega',
    //         'codigoproducto' => 'required|array|min:1',
    //         'cantidad' => 'required|array|min:1',
    //     ]);

    //     try {
    //         DB::beginTransaction();

    //         $nota = TipoNota::where('codigo', $codigo)->firstOrFail();
    //         $nota->update([
    //             'tiponota' => $request->tiponota,
    //             'nro_identificacion' => $request->nro_identificacion,
    //             'idbodega' => $request->idbodega,
    //         ]);

    //         $nota->detalles()->delete();

    //         foreach ($request->codigoproducto as $index => $productoId) {
    //             DetalleTipoNota::create([
    //                 'tipo_nota_id' => $nota->codigo,
    //                 'codigoproducto' => $productoId,
    //                 'cantidad' => $request->cantidad[$index],
    //             ]);
    //         }

    //         DB::commit();
    //         return redirect()->route('tipoNota.index')->with('success', 'Nota actualizada correctamente.');
    //     } catch (QueryException $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Error al actualizar la nota.');
    //     }
    // }

    // /**
    //  * Elimina una nota.
    //  */
    // public function destroy($codigo)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $nota = TipoNota::where('codigo', $codigo)->firstOrFail();
    //         $nota->detalles()->delete();
    //         $nota->delete();
    //         DB::commit();

    //         return redirect()->route('tipoNota.index')->with('success', 'Nota eliminada correctamente.');
    //     } catch (QueryException $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Error al eliminar la nota.');
    //     }
    // }


    /**
     * Muestra el formulario para editar una nota.
     */
    public function edit($codigo)
    {
        $tipoNota = TipoNota::with(['detalles', 'transaccion'])->where('codigo', $codigo)->firstOrFail();
        
        // Verificar si la nota ya está confirmada
        if ($tipoNota->transaccion) {
            return redirect()->route('tipoNota.index')->with('error', 'No se puede editar una nota que ya está confirmada.');
        }
        
        $empleados = Empleado::all();
        $bodegas = Bodega::all();
        $productos = Producto::all();

        return view('tipoNota.edit', compact('tipoNota', 'empleados', 'bodegas', 'productos'));
    }

    /**
     * Actualiza una nota en la base de datos.
     */
    public function update(Request $request, $codigo)
    {
        $tipoNota = TipoNota::with('transaccion')->where('codigo', $codigo)->firstOrFail();
        
        // Verificar si la nota ya está confirmada
        if ($tipoNota->transaccion) {
            return redirect()->route('tipoNota.index')->with('error', 'No se puede actualizar una nota que ya está confirmada.');
        }
        
        $request->validate([
            'tiponota' => 'required|string|max:255',
            'nro_identificacion' => 'required|exists:empleados,nro_identificacion',
            'idbodega' => 'required|string|exists:bodegas,idbodega',
            'codigoproducto' => 'required|array|min:1',
            'cantidad' => 'required|array|min:1',
        ]);

        try {
            DB::beginTransaction();

            $tipoNota->update([
                'tiponota' => $request->tiponota,
                'nro_identificacion' => $request->nro_identificacion,
                'idbodega' => $request->idbodega,
            ]);

            $tipoNota->detalles()->delete();

            foreach ($request->codigoproducto as $index => $productoId) {
                DetalleTipoNota::create([
                    'tipo_nota_id' => $tipoNota->codigo,
                    'codigoproducto' => $productoId,
                    'cantidad' => $request->cantidad[$index],
                ]);
            }

            DB::commit();
            return redirect()->route('tipoNota.index')->with('success', 'Nota actualizada correctamente.');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar la nota.');
        }
    }

    /**
     * Elimina una nota.
     */
    public function destroy($codigo)
    {
        $nota = TipoNota::with('transaccion')->where('codigo', $codigo)->firstOrFail();
        
        // Verificar si la nota ya está confirmada
        if ($nota->transaccion) {
            return redirect()->route('tipoNota.index')->with('error', 'No se puede eliminar una nota que ya está confirmada.');
        }
        
        try {
            DB::beginTransaction();
            $nota->detalles()->delete();
            $nota->delete();
            DB::commit();

            return redirect()->route('tipoNota.index')->with('success', 'Nota eliminada correctamente.');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al eliminar la nota.');
        }
    }
    /**
     * Genera un PDF con la información de una nota.
     */

    public function generarPDF($codigo)
    {
        // Buscar la nota por código
        $nota = TipoNota::with(['responsableEmpleado', 'bodega', 'detalles.producto', 'transaccion'])
            ->where('codigo', $codigo)
            ->firstOrFail();

        // Verificar si la transacción existe y está confirmada
        if ($nota->transaccion === null ) {
            return redirect()->back()->with('error', 'La nota debe estar confirmada antes de generar el PDF.');
        }

        // Si la transacción está confirmada, proceder a generar el PDF
        $pdf = Pdf::loadView('tipoNota.pdf', compact('nota'));

        return $pdf->download("Nota_{$nota->codigo}.pdf");
    }

    // public function productosPorBodega($id)
    // {
    //     // Obtiene los códigos de productos con stock en la bodega seleccionada
    //     $codigos = DB::table('productos_bodega')
    //         ->where('bodega_id', $id)
    //         ->where('cantidad', '>', 0)
    //         ->pluck('producto_id');

    //     // Devuelve los productos filtrados
    //     $productos = Producto::whereIn('codigo', $codigos)
    //         ->get(['codigo', 'nombre', 'cantidad', 'tipoempaque']);

    //     return response()->json($productos);
    // }





//     public function productosPorBodega($id)
// {
//     // Obtener productos con stock en la bodega seleccionada
//     $productos = Producto::select('productos.codigo', 'productos.nombre', 'productos.tipoempaque')
//         ->join('productos_bodega', 'productos.codigo', '=', 'productos_bodega.producto_id')
//         ->where('productos_bodega.bodega_id', $id)
//         ->where('productos_bodega.cantidad', '>', 0)
//         ->groupBy('productos.codigo', 'productos.nombre', 'productos.tipoempaque')
//         ->get();

//     // Calcular stock por bodega para cada producto
//     $productos->each(function ($producto) use ($id) {
//         // Stock en la bodega seleccionada
//         $stockBodega = DB::table('productos_bodega')
//             ->where('bodega_id', $id)
//             ->where('producto_id', $producto->codigo)
//             ->selectRaw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as stock')
//             ->value('stock') ?? 0;

//         // Stock general (suma de todas las bodegas)
//         $stockGeneral = DB::table('productos_bodega')
//             ->where('producto_id', $producto->codigo)
//             ->selectRaw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as stock')
//             ->value('stock') ?? 0;

//         // Agregar los datos al producto
//         $producto->cantidad = $stockGeneral; // Stock general
//         $producto->stocks_por_bodega = [
//             [
//                 'idbodega' => (int)$id,
//                 'cantidad' => $stockBodega
//             ]
//         ];
//     });

//     return response()->json($productos);
// }


    // public function productosMaster()
    // {
    //     // Busca la bodega master
    //     $masterBodega = \App\Models\Bodega::where('nombrebodega', 'MASTER')->first();
    //     $productos = collect();
    //     if ($masterBodega) {
    //         // Obtiene los códigos de productos con stock en la bodega master
    //         $codigos = 
    //         DB::table('productos_bodega')
    //             ->where('bodega_id', $masterBodega->idbodega)
    //             ->where('cantidad', '>', 0)
    //             ->pluck('producto_id');

    //         // Devuelve solo los productos en stock de la bodega master
    //         $productos = \App\Models\Producto::whereIn('codigo', $codigos)
    //             ->get(['codigo', 'nombre', 'cantidad', 'tipoempaque']);
    //     }
    //     return response()->json($productos);
    // }
//     public function productosMaster()
// {
//     // Obtener todos los productos activos (o con stock) sin depender de bodega
//     $productos = \App\Models\Producto::query()
//         // Puedes añadir más condiciones si es necesario, por ejemplo:
//         // ->where('activo', true)
//         ->where('cantidad', '>', 0)
//         ->get(['codigo', 'nombre', 'cantidad', 'tipoempaque']);
    
//     return response()->json($productos);
// }
public function productosPorBodega($id)
{
    // Obtener productos con stock en la bodega seleccionada
    $productosConStock = DB::table('productos_bodega')
        ->select(
            'producto_id',
            DB::raw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as stock_bodega')
        )
        ->where('bodega_id', $id)
        ->groupBy('producto_id')
        ->havingRaw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) > 0')
        ->pluck('stock_bodega', 'producto_id');

    if ($productosConStock->isEmpty()) {
        return response()->json([]);
    }

    // Obtener información completa de los productos
    $productos = Producto::select('codigo', 'nombre', 'tipoempaque', 'cantidad')
        ->whereIn('codigo', $productosConStock->keys())
        ->get();

    // Agregar el stock por bodega a cada producto
    $productos->each(function ($producto) use ($productosConStock, $id) {
        $stockBodega = $productosConStock->get($producto->codigo, 0);
        
        // Stock general del producto (tabla productos)
        $producto->cantidad = $producto->cantidad ?? 0;
        
        // Stock específico en esta bodega
        $producto->stocks_por_bodega = [
            [
                'idbodega' => (int)$id,
                'cantidad' => $stockBodega
            ]
        ];
    });

    return response()->json($productos);
}

public function productosMaster()
{
    // La bodega MASTER es conceptual - usar directamente la tabla productos
    // Obtener productos con stock disponible
    $productos = \App\Models\Producto::query()
        ->where('cantidad', '>', 0)
        ->get(['codigo', 'nombre', 'cantidad', 'tipoempaque']);

    // Agregar información adicional para compatibilidad con el frontend
    $productos->each(function ($producto) {
        $producto->stocks_por_bodega = [
            [
                'idbodega' => 'master',
                'cantidad' => $producto->cantidad
            ]
        ];
    });
    
    return response()->json($productos);
}


}
