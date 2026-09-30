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
        $this->authorize('create', \App\Models\Venta::class);

        $bodega = Bodega::findOrFail($bodega_id);

        // Calcula el próximo número de venta SOLO para esta bodega
        $nroVenta = \App\Models\Venta::where('bodega_id', $bodega_id)->max('nro_venta');
        $nroVenta = $nroVenta ? $nroVenta + 1 : 1;

        // Solo productos con stock en la bodega
        // Optimizado con JOIN para evitar N+1 queries
        $productos = DB::table('productos_bodega as pb')
            ->join('productos as p', 'pb.producto_id', '=', 'p.codigo')
            ->select(
                'p.codigo',
                'p.nombre',
                'p.tipoempaque',
                DB::raw('SUM(CASE WHEN pb.es_devolucion = false THEN pb.cantidad ELSE 0 END) - SUM(CASE WHEN pb.es_devolucion = true THEN pb.cantidad ELSE 0 END) as stock')
            )
            ->where('pb.bodega_id', $bodega_id)
            ->groupBy('p.codigo', 'p.nombre', 'p.tipoempaque')
            ->havingRaw('SUM(CASE WHEN pb.es_devolucion = false THEN pb.cantidad ELSE 0 END) - SUM(CASE WHEN pb.es_devolucion = true THEN pb.cantidad ELSE 0 END) > 0')
            ->get()
            ->map(fn($row) => [
                'codigo' => $row->codigo,
                'nombre' => $row->nombre,
                'stock'  => (int) $row->stock,
                'tipoempaque' => $row->tipoempaque ?? 'Unidad',
            ]);

        return view('venta.create', compact('bodega', 'productos', 'nroVenta'));
    }

    public function store(Request $request, $bodega_id)
    {
        $this->authorize('create', \App\Models\Venta::class);
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

        // Precargar productos y stock en 2 queries (evita N+1)
        $productosMap = Producto::whereIn('codigo', $request->producto_id)->get()->keyBy('codigo');
        $stockMap = DB::table('productos_bodega')
            ->select('producto_id', DB::raw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as stock'))
            ->where('bodega_id', $bodega_id)
            ->whereIn('producto_id', $request->producto_id)
            ->groupBy('producto_id')
            ->pluck('stock', 'producto_id');

        // Verificar stock de TODOS los productos antes de crear la venta
        $totalVenta = 0;
        foreach ($request->producto_id as $index => $codigo) {
            $cantidadSolicitada = $request->cantidad[$index];
            $totalVenta += $cantidadSolicitada * $request->precio_unitario[$index];

            $stock = $stockMap->get($codigo, 0);
            $producto = $productosMap->get($codigo);
            $nombreProducto = $producto ? $producto->nombre : $codigo;

            if ($cantidadSolicitada > $stock) {
                return back()->withInput()->with('error', "No hay suficiente stock para el producto \"{$nombreProducto}\" (Código: {$codigo}). Stock disponible: {$stock}, cantidad solicitada: {$cantidadSolicitada}.");
            }
        }

        // Calcula el próximo número de venta SOLO para esta bodega
        $nroVenta = \App\Models\Venta::where('bodega_id', $bodega_id)->max('nro_venta');
        $nroVenta = $nroVenta ? $nroVenta + 1 : 1;

        // Guarda la venta (cabecera) - Solo si todo el stock fue validado correctamente
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

        // Registrar en la auditoría
        \App\Helpers\AuditoriaHelper::registrar(
            'Ventas',
            'Creación',
            "Registró una nueva venta Nro. {$nroVenta} al cliente '{$request->cliente}' por un total de $" . number_format($request->total_venta, 2)
        );

        // Redirige al index de ventas después de guardar
        return redirect()->route('venta.index.bodega', $bodega_id)->with('success', 'Venta registrada correctamente.');
    }

    public function indexPorBodega($bodega_id)
    {
        $this->authorize('viewAny', \App\Models\Venta::class);

        $bodega = Bodega::findOrFail($bodega_id);
        $ventas = Venta::where('bodega_id', $bodega_id)->with(['bodega', 'abonos'])->get();

        // Calcula el saldo usando abonos precargados (sin queries adicionales)
        foreach ($ventas as $venta) {
            if ($venta->tipo_pago === 'Crédito') {
                $venta->saldo = $venta->total_venta - $venta->abonos->sum('abono');
            }
        }

        return view('venta.index', compact('ventas', 'bodega'));
    }

    public function index()
    {
        $this->authorize('viewAny', \App\Models\Venta::class);
        $ventas = Venta::with(['bodega', 'abonos'])->get();

        foreach ($ventas as $venta) {
            if ($venta->tipo_pago === 'Crédito') {
                $venta->saldo = $venta->total_venta - $venta->abonos->sum('abono');
            }
        }

        return view('venta.index', compact('ventas'));
    }

    public function show($id)
    {
        $venta = Venta::with(['bodega', 'detalles.producto'])->findOrFail($id);
        $this->authorize('view', $venta);
        $abonos = [];
        if ($venta->tipo_pago === 'Crédito') {
            $abonos = \App\Models\Abono::where('venta_id', $venta->id)->get();
        }
        return view('venta.show', compact('venta', 'abonos'));
    }

    public function abonoForm($id)
    {
        $venta = Venta::with(['bodega', 'detalles.producto'])->findOrFail($id);
        $this->authorize('manageCuentasPorCobrar', $venta);
        $abonos = \App\Models\Abono::where('venta_id', $venta->id)->get();
        $saldo = $venta->total_venta - $abonos->sum('abono');
        return view('venta.abono', compact('venta', 'abonos', 'saldo'));
    }

    public function agregarAbono(Request $request, $id)
    {
        $venta = Venta::findOrFail($id);
        $this->authorize('manageCuentasPorCobrar', $venta);

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
        $this->authorize('update', $venta);
        $bodega = $venta->bodega;
        $productos = Producto::all();
        return view('venta.edit', compact('venta', 'bodega', 'productos'));
    }

    public function update(Request $request, $id)
    {
        $venta = Venta::findOrFail($id);
        $this->authorize('update', $venta);

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
        $this->authorize('delete', $venta);
        $venta->delete();
        return redirect()->route('venta.index.bodega', $venta->bodega_id)->with('success', 'Venta eliminada correctamente.');
    }

    public function exportarVentas(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Venta::class);
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
