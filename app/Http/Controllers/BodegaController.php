<?php

namespace App\Http\Controllers;

use App\Models\Bodega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controller;  
use Illuminate\Support\Facades\DB;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Asegúrate de importar esto

class BodegaController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {  
        //$this->authorizeResource(Bodega::class, 'bodega'); // ✅ Debe coincidir con la ruta
    }

    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $cargo = auth()->user()->cargoNombre();
        if (in_array($cargo, ['Vendedor', 'Vendedor camión', 'Jefe de bodega'])) {
         abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $bodegas = Bodega::orderBy('nombrebodega', 'ASC')->paginate(5);
        return view('bodegas.index', compact('bodegas'));
    }

    
public function stockPdf($id)
{
    $bodega = Bodega::findOrFail($id);

    $productosEnBodega = DB::table('productos_bodega')
        ->select('producto_id', DB::raw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) as enviados'), DB::raw('SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as devueltos'))
        ->where('bodega_id', $id)
        ->groupBy('producto_id')
        ->get()
        ->map(function($row) {
            $producto = \App\Models\Producto::where('codigo', $row->producto_id)->first();
            $cantidad = ($row->enviados - $row->devueltos);
            return $cantidad > 0 && $producto ? [
                'codigo'      => $producto->codigo,
                'nombre'      => $producto->nombre,
                'descripcion' => $producto->descripcion,
                'cantidad'    => $cantidad,
                'empaque'     => $producto->tipoempaque ?? '',
            ] : null;
        })
        ->filter()
        ->values();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.stock_bodega', [
        'bodega' => $bodega,
        'productosEnBodega' => $productosEnBodega,
    ]);
    return $pdf->stream('stock_bodega_' . $bodega->nombrebodega . '.pdf');
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $cargo = auth()->user()->cargoNombre();
        if (in_array($cargo, ['Vendedor', 'Vendedor camión', 'Jefe de bodega'])) {
         abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return view('bodegas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $cargo = auth()->user()->cargoNombre();
        if (in_array($cargo, ['Vendedor', 'Vendedor camión', 'Jefe de bodega'])) {
         abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        // Ya no es necesario validar el idbodega porque es autoincremental
        $request->validate([
            'nombrebodega' => 'required|max:10',  // Solo validamos el nombre
        ]);

        Bodega::create($request->all());  // Aquí idbodega será asignado automáticamente
        return redirect()->route('bodegas.index')->with('success', 'Registro creado satisfactoriamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         $cargo = auth()->user()->cargoNombre();
        if (in_array($cargo, ['Vendedor', 'Vendedor camión', 'Jefe de bodega'])) {
         abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        $bodega = Bodega::findOrFail($id);

        // Productos en stock en la bodega
        $productosEnBodega = DB::table('productos_bodega')
            ->select('producto_id', DB::raw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) as enviados'), DB::raw('SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as devueltos'))
            ->where('bodega_id', $id)
            ->groupBy('producto_id')
            ->get()
            ->map(function($row) {
                $producto = \App\Models\Producto::where('codigo', $row->producto_id)->first();
                $cantidad = ($row->enviados - $row->devueltos);
                return $cantidad > 0 && $producto ? [
                    'codigo'      => $producto->codigo,
                    'nombre'      => $producto->nombre,
                    'descripcion' => $producto->descripcion, // <--- Agregado
                    'cantidad'    => $cantidad,
                    'empaque'     => $producto->tipoempaque ?? '',
                ] : null;
            })
            ->filter()
            ->values();

        // Productos enviados y devueltos (si los usas en la vista)
        $productos = DB::table('productos_bodega')
            ->join('productos', 'productos.codigo', '=', 'productos_bodega.producto_id')
            ->where('productos_bodega.bodega_id', $id)
            ->where('productos_bodega.es_devolucion', false)
            ->select(
                'productos.codigo',
                'productos.nombre',
                'productos_bodega.cantidad',
                'productos_bodega.fecha'
            )
            ->get();

        $devueltos = DB::table('productos_bodega')
            ->join('productos', 'productos.codigo', '=', 'productos_bodega.producto_id')
            ->where('productos_bodega.bodega_id', $id)
            ->where('productos_bodega.es_devolucion', true)
            ->select(
                'productos.codigo',
                'productos.nombre',
                'productos_bodega.cantidad',
                'productos_bodega.fecha'
            )
            ->get();

        return view('home.bodega', compact('bodega', 'productosEnBodega', 'productos', 'devueltos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $idbodega)
    {
         $cargo = auth()->user()->cargoNombre();
        if (in_array($cargo, ['Vendedor', 'Vendedor camión', 'Jefe de bodega'])) {
         abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        $bodega = Bodega::findOrFail($idbodega);
        return view('bodegas.edit', compact('bodega'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $idbodega)
    {
         $cargo = auth()->user()->cargoNombre();
        if (in_array($cargo, ['Vendedor', 'Vendedor camión', 'Jefe de bodega'])) {
         abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        $request->validate([
            'nombrebodega' => 'required|max:10',  // Solo validamos el nombre
        ]);

        // No es necesario enviar 'idbodega' porque será un campo autoincremental
        $bodega = Bodega::findOrFail($idbodega);
        $bodega->update($request->all());
        
        return redirect()->route('bodegas.index')->with('success', 'Registro actualizado satisfactoriamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $idbodega)
    {
        
        $cargo = auth()->user()->cargoNombre();
        if (in_array($cargo, ['Vendedor', 'Vendedor camión', 'Jefe de bodega'])) {
         abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        Bodega::findOrFail($idbodega)->delete();
        return redirect()->route('bodegas.index')->with('success', 'Registro eliminado satisfactoriamente');
    }

    /**
     * Display a listing of all products in the master bodega.
     */
    // Para ENVÍO: muestra todos los productos registrados
    public function productosMaster()
    {
        $productos = \App\Models\Producto::all()->map(function($producto) {
            return [
                'codigo'      => $producto->codigo,
                'nombre'      => $producto->nombre,
                'cantidad'    => $producto->cantidad ?? 0,
                'empaque'     => $producto->tipoempaque ?? '', // <-- usa el nombre correcto del campo
            ];
        });

        return response()->json($productos);
    }

    /**
     * Display a listing of the products in the specified bodega.
     */
    // Para DEVOLUCIÓN: solo productos con stock en la bodega seleccionada
    public function productosEnBodega($id)
    {
        $productos = DB::table('productos_bodega')
            ->select('producto_id', DB::raw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) as enviados'), DB::raw('SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as devueltos'))
            ->where('bodega_id', $id)
            ->groupBy('producto_id')
            ->get()
            ->map(function($row) {
                $producto = \App\Models\Producto::where('codigo', $row->producto_id)->first();
                $cantidad = ($row->enviados - $row->devueltos);
                return $cantidad > 0 && $producto ? [
                    'codigo'      => $producto->codigo,
                    'nombre'      => $producto->nombre,
                    'cantidad'    => $cantidad,
                    'empaque'     => $producto->tipoempaque ?? '', // <-- usa el nombre correcto del campo
                ] : null;
            })
            ->filter()
            ->values();

        return response()->json($productos);
    }
}
