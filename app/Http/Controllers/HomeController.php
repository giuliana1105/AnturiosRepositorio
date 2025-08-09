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

        // Productos enviados
        $productosEnviados = $bodega->productosEnviados()->get();

        // Productos devueltos
        $productosDevueltos = $bodega->productosDevueltos()->get();

        // Productos en bodega (stock actual)
        $productosEnBodega = DB::table('productos_bodega')
            ->select('producto_id', DB::raw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) as enviados'), DB::raw('SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as devueltos'))
            ->where('bodega_id', $id)
            ->groupBy('producto_id')
            ->get()
            ->map(function($row) {
                $producto = \App\Models\Producto::where('codigo', $row->producto_id)->first();
                return [
                    'codigo'      => $producto->codigo ?? '',
                    'nombre'      => $producto->nombre ?? '',
                    'descripcion' => $producto->descripcion ?? '',
                    'cantidad'    => ($row->enviados - $row->devueltos),
                ];
            });

        return view('home.bodega', [
            'bodega' => $bodega,
            'productos' => $productosEnviados,
            'devueltos' => $productosDevueltos,
            'productosEnBodega' => $productosEnBodega,
        ]);
    }
}