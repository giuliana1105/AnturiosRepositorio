<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bodega;
use App\Models\Producto;
use App\Models\Venta; // Nuevo modelo para cabecera
use App\Models\DetalleVentaBodega; // Nuevo modelo para detalle

class VentaBodegaController extends Controller
{
    public function create($bodega_id)
    {
        $bodega = Bodega::findOrFail($bodega_id);

        // Solo productos con stock en la bodega
        $productos = DB::table('productos_bodega')
            ->select('producto_id', DB::raw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as stock'))
            ->where('bodega_id', $bodega_id)
            ->groupBy('producto_id')
            ->havingRaw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) > 0')
            ->get()
            ->map(function($row) {
                $producto = Producto::where('codigo', $row->producto_id)->first();
                return [
                    'codigo' => $producto->codigo,
                    'nombre' => $producto->nombre,
                    'stock'  => $row->stock,
                    'tipoempaque' => $producto->tipoempaque ?? 'Unidad',
                ];
            });

        return view('venta.create', compact('bodega', 'productos'));
    }

    public function store(Request $request, $bodega_id)
    {
        $request->validate([
            'producto_id' => 'required|array|min:1',
            'producto_id.*' => 'required|exists:productos,codigo',
            'cantidad' => 'required|array|min:1',
            'cantidad.*' => 'required|integer|min:1',
            'precio_unitario' => 'required|array|min:1',
            'precio_unitario.*' => 'required|numeric|min:0.01',
        ]);

        // Guarda la venta (cabecera)
        $venta = Venta::create([
            'bodega_id' => $bodega_id,
            'fecha' => now(),
        ]);

        foreach ($request->producto_id as $index => $codigo) {
            // Verifica stock
            $stock = DB::table('productos_bodega')
                ->where('bodega_id', $bodega_id)
                ->where('producto_id', $codigo)
                ->selectRaw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as stock')
                ->value('stock') ?? 0;

            if ($request->cantidad[$index] > $stock) {
                return back()->with('error', 'No hay suficiente stock para el producto ' . $codigo);
            }

            // Guarda el detalle de la venta
            DetalleVentaBodega::create([
                'venta_id' => $venta->id,
                'producto_id' => $codigo,
                'cantidad' => $request->cantidad[$index],
                'tipoempaque' => 'Unidad',
                'precio_unitario' => $request->precio_unitario[$index],
                'precio_total' => $request->cantidad[$index] * $request->precio_unitario[$index],
            ]);

            // Actualiza el stock en productos_bodega (registra salida)
            DB::table('productos_bodega')->insert([
                'bodega_id' => $bodega_id,
                'producto_id' => $codigo,
                'cantidad' => $request->cantidad[$index],
                'fecha' => now(),
                'es_devolucion' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Redirige al index de ventas después de guardar
        return redirect()->route('venta.index')->with('success', 'Venta registrada correctamente.');
    }

    public function index()
    {
        $ventas = \App\Models\Venta::with(['bodega', 'detalles.producto'])->orderBy('fecha', 'desc')->get();
        return view('venta.index', compact('ventas'));
    }
}
