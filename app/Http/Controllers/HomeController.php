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

        if (!$user->can('ver dashboard general') && $user->empleado && $user->empleado->bodega) {
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

            // Productos en bodega (stock actual) - optimizado con JOIN
            $productosEnBodega = DB::table('productos_bodega as pb')
                ->join('productos as p', 'pb.producto_id', '=', 'p.codigo')
                ->select(
                    'p.codigo',
                    'p.nombre',
                    'p.descripcion',
                    DB::raw('SUM(CASE WHEN pb.es_devolucion = false THEN pb.cantidad ELSE 0 END) - SUM(CASE WHEN pb.es_devolucion = true THEN pb.cantidad ELSE 0 END) as cantidad')
                )
                ->where('pb.bodega_id', $id)
                ->groupBy('p.codigo', 'p.nombre', 'p.descripcion')
                ->havingRaw('SUM(CASE WHEN pb.es_devolucion = false THEN pb.cantidad ELSE 0 END) - SUM(CASE WHEN pb.es_devolucion = true THEN pb.cantidad ELSE 0 END) > 0')
                ->get()
                ->map(fn($row) => [
                    'codigo'      => $row->codigo,
                    'nombre'      => $row->nombre ?? 'Producto no encontrado',
                    'descripcion' => $row->descripcion ?? '',
                    'cantidad'    => (int) $row->cantidad,
                ]);

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
        if (!auth()->user()->can('ver dashboard general')) {
            abort(403, 'No tienes permiso para ver el dashboard general.');
        }

        $productos = Producto::all();
        $empleados = Empleado::all();
        $bodegas = Bodega::all();
        $tiposNota = TipoNota::all();
        $transacciones = TransaccionProducto::all();
        return view('home.master', compact('productos', 'empleados', 'bodegas', 'tiposNota', 'transacciones'));
    }

    public function bodega($id)
    {
        if (!auth()->user()->can('ver dashboard general')) {
            abort(403, 'No tienes permiso para ver bodegas de otros usuarios.');
        }

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

        // Productos en bodega (stock actual) - optimizado con JOIN
        $productosEnBodega = DB::table('productos_bodega as pb')
            ->join('productos as p', 'pb.producto_id', '=', 'p.codigo')
            ->select(
                'p.codigo',
                'p.nombre',
                'p.descripcion',
                DB::raw('SUM(CASE WHEN pb.es_devolucion = false THEN pb.cantidad ELSE 0 END) - SUM(CASE WHEN pb.es_devolucion = true THEN pb.cantidad ELSE 0 END) as cantidad')
            )
            ->where('pb.bodega_id', $id)
            ->groupBy('p.codigo', 'p.nombre', 'p.descripcion')
            ->havingRaw('SUM(CASE WHEN pb.es_devolucion = false THEN pb.cantidad ELSE 0 END) - SUM(CASE WHEN pb.es_devolucion = true THEN pb.cantidad ELSE 0 END) > 0')
            ->get()
            ->map(fn($row) => [
                'codigo'      => $row->codigo,
                'nombre'      => $row->nombre ?? 'Producto no encontrado',
                'descripcion' => $row->descripcion ?? '',
                'cantidad'    => (int) $row->cantidad,
            ]);

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