<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bodega;
use App\Models\Producto;
use App\Models\Venta; // Nuevo modelo para cabecera
use App\Models\DetalleVentaBodega; // Nuevo modelo para detalle
use App\Models\Abono;
use Barryvdh\DomPDF\Facade\Pdf;

class VentaBodegaController extends Controller
{
    public function create($bodega_id)
    {
        $cargo = auth()->user()->cargoNombre();
        if ($cargo === 'Vendedor') {
            abort(403, 'No tienes permiso para registrar ventas.');
        }

        $bodega = Bodega::findOrFail($bodega_id);

        // Calcula el próximo número de venta SOLO para esta bodega
        $nroVenta = \App\Models\Venta::where('bodega_id', $bodega_id)->max('nro_venta');
        $nroVenta = $nroVenta ? $nroVenta + 1 : 1;

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

        return view('venta.create', compact('bodega', 'productos', 'nroVenta'));
    }

    public function store(Request $request, $bodega_id)
    {
        // Depuración
         //dd($request->all());

        $request->validate([
            'producto_id' => 'required|array|min:1',
            'producto_id.*' => 'required|exists:productos,codigo',
            'cantidad' => 'required|array|min:1',
            'cantidad.*' => 'required|integer|min:1',
            'precio_unitario' => 'required|array|min:1',
            'precio_unitario.*' => 'required|numeric|min:0.01',
            'cliente' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255', // <-- Nueva validación
            'tipo_pago' => 'required|in:Efectivo,Transferencia,Crédito,Cheque',
        ]);

        $totalVenta = 0;
        foreach ($request->producto_id as $index => $codigo) {
            $totalVenta += $request->cantidad[$index] * $request->precio_unitario[$index];
        }

        // Calcula el próximo número de venta SOLO para esta bodega
        $nroVenta = \App\Models\Venta::where('bodega_id', $bodega_id)->max('nro_venta');
        $nroVenta = $nroVenta ? $nroVenta + 1 : 1;

        // Guarda la venta (cabecera)
        $venta = Venta::create([
            'bodega_id' => $bodega_id,
            'nro_venta' => $nroVenta,
            'fecha' => now(),
            'cliente' => $request->cliente,
            'ciudad' => $request->ciudad,
            'total_venta' => $totalVenta,
            'tipo_pago' => $request->tipo_pago,
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

            // Actualiza el stock en productos_bodega (registra salida por venta)
            DB::table('productos_bodega')->insert([
                'bodega_id' => $bodega_id,
                'producto_id' => $codigo,
                'cantidad' => -abs($request->cantidad[$index]), // RESTA STOCK
                'fecha' => now(),
                'es_devolucion' => false,
                'tipo_movimiento' => 'venta', // Identifica como venta
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        

        // Guardar abonos si es crédito
        if ($request->tipo_pago === 'Crédito' && $request->has('abono')) {
            foreach ($request->abono as $index => $valorAbono) {
                $tipoPago = is_array($request->tipo_pago_abono)
                    ? ($request->tipo_pago_abono[$index] ?? null)
                    : $request->tipo_pago_abono;

                $fechaAbono = is_array($request->fecha_abono)
                    ? ($request->fecha_abono[$index] ?? now())
                    : ($request->fecha_abono ?? now());

                if ($valorAbono && $tipoPago) {
                    \App\Models\Abono::create([
                        'venta_id' => $venta->id,
                        'abono' => $valorAbono,
                        'fecha' => $fechaAbono,
                        'tipo_pago' => $tipoPago,
                    ]);
                }
            }
        }

        // Redirige al index de ventas después de guardar
        return redirect()->route('venta.index.bodega', $bodega_id)->with('success', 'Venta registrada correctamente.');
    }

    public function indexPorBodega($bodega_id)
    {
        $cargo = auth()->user()->cargoNombre();
        if ($cargo === 'Vendedor') {
            abort(403, 'No tienes permiso para ver ventas.');
        }

        $bodega = Bodega::findOrFail($bodega_id);
        $ventas = Venta::where('bodega_id', $bodega_id)->with('bodega')->get();

        // Calcula el saldo para cada venta de crédito
        foreach ($ventas as $venta) {
            if ($venta->tipo_pago === 'Crédito') {
                $abonos = \App\Models\Abono::where('venta_id', $venta->id)->sum('abono');
                $venta->saldo = $venta->total_venta - $abonos;
            }
        }

        return view('venta.index', compact('ventas', 'bodega'));
    }

    public function index()
    {
        $ventas = Venta::with('bodega')->get();

        foreach ($ventas as $venta) {
            if ($venta->tipo_pago === 'Crédito') {
                $abonos = \App\Models\Abono::where('venta_id', $venta->id)->sum('abono');
                $venta->saldo = $venta->total_venta - $abonos;
            }
        }

        return view('venta.index', compact('ventas'));
    }

    public function show($id)
    {
        $venta = Venta::with(['bodega', 'detalles.producto'])->findOrFail($id);
        $abonos = [];
        if ($venta->tipo_pago === 'Crédito') {
            $abonos = \App\Models\Abono::where('venta_id', $venta->id)->get();
        }
        return view('venta.show', compact('venta', 'abonos'));
    }

    public function abonoForm($id)
    {
        $venta = Venta::with(['bodega', 'detalles.producto'])->findOrFail($id);
        $abonos = \App\Models\Abono::where('venta_id', $venta->id)->get();
        $saldo = $venta->total_venta - $abonos->sum('abono');
        return view('venta.abono', compact('venta', 'abonos', 'saldo'));
    }

    public function agregarAbono(Request $request, $id)
    {
        $venta = Venta::findOrFail($id);

        $request->validate([
            'abono' => 'required|numeric|min:0.01',
            'fecha_abono' => 'required|date',
            'tipo_pago_abono' => 'required|string',
        ]);

        \App\Models\Abono::create([
            'venta_id' => $venta->id,
            'abono' => $request->abono,
            'fecha' => $request->fecha_abono,
            'tipo_pago' => $request->tipo_pago_abono,
        ]);

        // Redirige al index de ventas de la bodega correspondiente
        return redirect()->route('venta.abono', $venta->id)->with('success', 'Abono agregado correctamente.');
    }

    public function edit($id)
    {
        $venta = Venta::with(['bodega', 'detalles.producto'])->findOrFail($id);
        $bodega = $venta->bodega;
        $productos = Producto::all();
        return view('venta.edit', compact('venta', 'bodega', 'productos'));
    }

    public function update(Request $request, $id)
    {
        $venta = Venta::findOrFail($id);

        $request->validate([
            'cliente' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'tipo_pago' => 'required|in:Efectivo,Transferencia,Crédito,Cheque',
            'producto_id.*' => 'required|exists:productos,codigo',
            'tipoempaque.*' => 'required|string',
            'cantidad.*' => 'required|numeric|min:1',
            'precio_unitario.*' => 'required|numeric|min:0',
            'precio_total.*' => 'required|numeric|min:0',
        ]);

        $venta->update([
            'cliente' => $request->cliente,
            'ciudad' => $request->ciudad,
            'tipo_pago' => $request->tipo_pago,
        ]);

        // Actualiza los detalles
        foreach ($venta->detalles as $i => $detalle) {
            $detalle->update([
                'producto_id' => $request->producto_id[$i],
                'tipoempaque' => $request->tipoempaque[$i],
                'cantidad' => $request->cantidad[$i],
                'precio_unitario' => $request->precio_unitario[$i],
                'precio_total' => $request->precio_total[$i],
            ]);
        }

        return redirect()->route('venta.index.bodega', $venta->bodega_id)->with('success', 'Venta actualizada correctamente.');
    }

    public function destroy($id)
    {
        $venta = Venta::findOrFail($id);
        $venta->delete();
        return redirect()->route('venta.index.bodega', $venta->bodega_id)->with('success', 'Venta eliminada correctamente.');
    }

    public function exportarVentas(Request $request)
    {
        $ventas = Venta::with(['detalles.producto', 'abonos'])
            ->when($request->bodega_id, fn($q) => $q->where('bodega_id', $request->bodega_id))
            ->when($request->cliente, fn($q) => $q->where('cliente', 'like', '%'.$request->cliente.'%'))
            ->when($request->ciudad, fn($q) => $q->where('ciudad', $request->ciudad))
            ->when($request->tipo_pago, function($q) use ($request) {
                if ($request->tipo_pago === 'Crédito liquidado' || $request->tipo_pago === 'Crédito pendiente') {
                    $q->where('tipo_pago', 'Crédito');
                } elseif ($request->tipo_pago) {
                    $q->where('tipo_pago', $request->tipo_pago);
                }
            })
            ->when($request->dia, fn($q) => $q->whereDate('fecha', $request->dia))
            ->when($request->fecha_inicio, fn($q) => $q->whereDate('fecha', '>=', $request->fecha_inicio))
            ->when($request->fecha_fin, fn($q) => $q->whereDate('fecha', '<=', $request->fecha_fin))
            ->get();

        // Calcula el saldo para cada venta de crédito
        foreach ($ventas as $venta) {
            if ($venta->tipo_pago === 'Crédito' && $venta->relationLoaded('abonos')) {
                $abonos = $venta->abonos->sum('abono');
                $venta->saldo = $venta->total_venta - $abonos;
            }
        }

        // Filtros especiales para crédito liquidado/pendiente
        if ($request->tipo_pago === 'Crédito liquidado') {
            $ventas = $ventas->filter(fn($venta) => $venta->tipo_pago === 'Crédito' && isset($venta->saldo) && $venta->saldo == 0);
        } elseif ($request->tipo_pago === 'Crédito pendiente') {
            $ventas = $ventas->filter(fn($venta) => $venta->tipo_pago === 'Crédito' && isset($venta->saldo) && $venta->saldo > 0);
        }

        $pdf = Pdf::loadView('venta.pdf', ['ventas' => $ventas]);
        return $pdf->stream('reporte_ventas.pdf');
    }
}
