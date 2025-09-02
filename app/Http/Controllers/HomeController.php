<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bodega;
use App\Models\Producto;
use App\Models\Empleado;
use App\Models\TipoNota;
use App\Models\TransaccionProducto;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $cargo = $user->cargoNombre();

        if (in_array($cargo, ['Vendedor', 'Vendedor camión'])) {
            $bodega = $user->empleado->bodega;
            $id = $bodega->idbodega;

            // Productos enviados a esta bodega (envíos normales, no devoluciones)
            $productosEnviados = DB::table('productos_bodega as pb')
                ->join('productos as p', 'pb.producto_id', '=', 'p.codigo')
                ->where('pb.bodega_id', $id)
                ->where('pb.es_envio', true) // Solo envíos normales
                ->select('p.codigo', 'p.nombre', 'pb.cantidad', 'pb.fecha')
                ->orderBy('pb.fecha', 'desc')
                ->get();

            // Productos devueltos desde esta bodega
            $productosDevueltos = DB::table('productos_bodega as pb')
                ->join('productos as p', 'pb.producto_id', '=', 'p.codigo')
                ->where('pb.bodega_id', $id)
                ->where('pb.es_devolucion', true)
                ->select('p.codigo', 'p.nombre', 'pb.cantidad', 'pb.fecha')
                ->orderBy('pb.fecha', 'desc')
                ->get();

            // Productos en bodega (stock actual)
            $productosEnBodega = DB::table('productos_bodega')
                ->select(
                    'producto_id',
                    DB::raw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) as enviados'),
                    DB::raw('SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as devueltos')
                )
                ->where('bodega_id', $id)
                ->groupBy('producto_id')
                ->havingRaw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) > 0')
                ->get()
                ->map(function($row) {
                    $producto = Producto::where('codigo', $row->producto_id)->first();
                    return [
                        'codigo'      => $producto->codigo ?? $row->producto_id,
                        'nombre'      => $producto->nombre ?? 'Producto no encontrado',
                        'descripcion' => $producto->descripcion ?? '',
                        'cantidad'    => ($row->enviados - $row->devueltos),
                    ];
                })
                ->filter(function($item) {
                    return $item['cantidad'] > 0;
                });

            return view('home.bodega', [
                'bodega' => $bodega,
                'productosEnBodega' => $productosEnBodega,
                'productos' => $productosEnviados,
                'devueltos' => $productosDevueltos,
            ]);
        }

        // Otros cargos ven todas las bodegas
        $bodegas = Bodega::all();
        return view('home', compact('bodegas'));
    }

    public function master()
    {
        $productos = Producto::all();
        $empleados = Empleado::all();
        $bodegas = Bodega::all();
        $tiposNota = TipoNota::all();
        $transacciones = TransaccionProducto::all();
        return view('home.master', compact('productos', 'empleados', 'bodegas', 'tiposNota', 'transacciones'));
    }

    public function bodega($id)
    {
        $bodega = Bodega::findOrFail($id);

        // Productos enviados a esta bodega (envíos normales, no devoluciones)
        $productosEnviados = DB::table('productos_bodega as pb')
            ->join('productos as p', 'pb.producto_id', '=', 'p.codigo')
            ->where('pb.bodega_id', $id)
            ->where('pb.es_devolucion', false) // Solo envíos normales
            ->select('p.codigo', 'p.nombre', 'pb.cantidad', 'pb.fecha')
            ->orderBy('pb.fecha', 'desc')
            ->get();

        // Productos devueltos desde esta bodega
        $productosDevueltos = DB::table('productos_bodega as pb')
            ->join('productos as p', 'pb.producto_id', '=', 'p.codigo')
            ->where('pb.bodega_id', $id)
            ->where('pb.es_devolucion', true)
            ->select('p.codigo', 'p.nombre', 'pb.cantidad', 'pb.fecha')
            ->orderBy('pb.fecha', 'desc')
            ->get();

        // Productos en bodega (stock actual)
        $productosEnBodega = DB::table('productos_bodega')
            ->select(
                'producto_id',
                DB::raw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) as enviados'),
                DB::raw('SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as devueltos')
            )
            ->where('bodega_id', $id)
            ->groupBy('producto_id')
            ->havingRaw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) > 0')
            ->get()
            ->map(function($row) {
                $producto = Producto::where('codigo', $row->producto_id)->first();
                return [
                    'codigo'      => $producto->codigo ?? $row->producto_id,
                    'nombre'      => $producto->nombre ?? 'Producto no encontrado',
                    'descripcion' => $producto->descripcion ?? '',
                    'cantidad'    => ($row->enviados - $row->devueltos),
                ];
            })
            ->filter(function($item) {
                return $item['cantidad'] > 0;
            });

        return view('home.bodega', [
            'bodega' => $bodega,
            'productos' => $productosEnviados,
            'devueltos' => $productosDevueltos,
            'productosEnBodega' => $productosEnBodega,
        ]);
    }

    // Método para debug - ver todos los registros de una bodega
    public function debugBodega($id)
    {
        $registros = DB::table('productos_bodega as pb')
            ->join('productos as p', 'pb.producto_id', '=', 'p.codigo')
            ->where('pb.bodega_id', $id)
            ->select('p.codigo', 'p.nombre', 'pb.cantidad', 'pb.fecha', 'pb.es_devolucion', 'pb.tipo_movimiento')
            ->orderBy('pb.fecha', 'desc')
            ->get();
        
        dd($registros);
    }
}