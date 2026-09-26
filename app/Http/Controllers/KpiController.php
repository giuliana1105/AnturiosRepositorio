<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bodega;

class KpiController extends Controller
{
    /**
     * Mapeo de ciudades de Ecuador a sus respectivas provincias.
     */
    private array $provinciasMap = [
        'quito' => 'Pichincha',
        'pichincha' => 'Pichincha',
        'guayaquil' => 'Guayas',
        'guayas' => 'Guayas',
        'ibarra' => 'Imbabura',
        'imbabura' => 'Imbabura',
        'otavalo' => 'Imbabura',
        'cotacachi' => 'Imbabura',
        'cuenca' => 'Azuay',
        'azuay' => 'Azuay',
        'ambato' => 'Tungurahua',
        'tungurahua' => 'Tungurahua',
        'santo domingo' => 'Santo Domingo',
        'machala' => 'El Oro',
        'el oro' => 'El Oro',
        'manta' => 'Manabí',
        'portoviejo' => 'Manabí',
        'manabi' => 'Manabí',
        'loja' => 'Loja',
        'esmeraldas' => 'Esmeraldas',
        'tulcan' => 'Carchi',
        'tulcán' => 'Carchi',
        'carchi' => 'Carchi',
        'riobamba' => 'Chimborazo',
        'chimborazo' => 'Chimborazo',
        'latacunga' => 'Cotopaxi',
        'cotopaxi' => 'Cotopaxi',
        'babahoyo' => 'Los Ríos',
        'quevedo' => 'Los Ríos',
        'los rios' => 'Los Ríos',
        'salinas' => 'Santa Elena',
        'santa elena' => 'Santa Elena',
        'sangolqui' => 'Pichincha',
        'sangolquí' => 'Pichincha',
        'cayambe' => 'Pichincha',
        'duran' => 'Guayas',
        'durán' => 'Guayas',
        'samborondon' => 'Guayas',
    ];

    public function index(Request $request)
    {
        $periodo = $request->get('periodo', 'ultimos_30_dias');
        $bodegaId = $request->get('bodega_id');

        // Determinar rango de fechas según período
        $startDate = match ($periodo) {
            'ultimos_7_dias' => now()->subDays(7),
            'ultimos_30_dias' => now()->subDays(30),
            'este_mes' => now()->startOfMonth(),
            'ultimo_trimestre' => now()->subMonths(3),
            'este_ano' => now()->startOfYear(),
            'todo' => null,
            default => now()->subDays(30),
        };

        // -------------------------------------------------------------
        // KPI 1: PRODUCTO MÁS VENDIDO
        // -------------------------------------------------------------
        $topProductosQuery = DB::table('detalle_venta_bodegas as dvb')
            ->join('ventas as v', 'dvb.venta_id', '=', 'v.id')
            ->join('productos as p', 'dvb.producto_id', '=', 'p.codigo');

        if ($startDate) {
            $topProductosQuery->where('v.fecha', '>=', $startDate);
        }
        if ($bodegaId) {
            $topProductosQuery->where('v.bodega_id', $bodegaId);
        }

        $topProductos = $topProductosQuery->select(
                'p.codigo',
                'p.nombre',
                DB::raw('SUM(dvb.cantidad) as total_unidades'),
                DB::raw('SUM(dvb.precio_total) as total_ingresos')
            )
            ->groupBy('p.codigo', 'p.nombre')
            ->orderByDesc('total_unidades')
            ->take(6)
            ->get();

        $totalUnidadesGlobal = $topProductos->sum('total_unidades');
        $topProductosFormatted = $topProductos->map(function ($item) use ($totalUnidadesGlobal) {
            $item->porcentaje = $totalUnidadesGlobal > 0 
                ? round(($item->total_unidades / $totalUnidadesGlobal) * 100, 1) 
                : 0;
            return $item;
        });

        // -------------------------------------------------------------
        // KPI 2: PORCENTAJE DE DEVOLUCIONES SEMANALES
        // -------------------------------------------------------------
        $semanasDevoluciones = [];
        $totalDevolucionesUltimoMes = 0;
        $totalMovimientosUltimoMes = 0;

        for ($i = 7; $i >= 0; $i--) {
            $startOfWeek = now()->subWeeks($i)->startOfWeek();
            $endOfWeek = now()->subWeeks($i)->endOfWeek();

            $enviosQuery = DB::table('productos_bodega')
                ->whereBetween('fecha', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
                ->where('es_devolucion', false);

            $devolucionesQuery = DB::table('productos_bodega')
                ->whereBetween('fecha', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
                ->where('es_devolucion', true);

            if ($bodegaId) {
                $enviosQuery->where('bodega_id', $bodegaId);
                $devolucionesQuery->where('bodega_id', $bodegaId);
            }

            $cantEnvios = (int) $enviosQuery->sum('cantidad');
            $cantDevoluciones = (int) $devolucionesQuery->sum('cantidad');
            $totalMovido = $cantEnvios + $cantDevoluciones;

            $porcentaje = $totalMovido > 0 
                ? round(($cantDevoluciones / $totalMovido) * 100, 2) 
                : 0;

            if ($i < 4) {
                $totalDevolucionesUltimoMes += $cantDevoluciones;
                $totalMovimientosUltimoMes += $totalMovido;
            }

            $semanasDevoluciones[] = [
                'semana' => 'Sem ' . $startOfWeek->format('W'),
                'fechas' => $startOfWeek->format('d/m') . ' - ' . $endOfWeek->format('d/m'),
                'envios' => $cantEnvios,
                'devoluciones' => $cantDevoluciones,
                'porcentaje' => $porcentaje,
            ];
        }

        $tasaDevolucionGlobal = $totalMovimientosUltimoMes > 0 
            ? round(($totalDevolucionesUltimoMes / $totalMovimientosUltimoMes) * 100, 2) 
            : 0;

        // -------------------------------------------------------------
        // KPI 3: ÍNDICE DE RECUPERACIÓN DE CARTERA
        // -------------------------------------------------------------
        $ventasCreditoQuery = DB::table('ventas')->where('tipo_pago', 'Crédito');
        if ($startDate) {
            $ventasCreditoQuery->where('fecha', '>=', $startDate);
        }
        if ($bodegaId) {
            $ventasCreditoQuery->where('bodega_id', $bodegaId);
        }

        $ventasCreditoList = $ventasCreditoQuery->get(['id', 'total_venta', 'fecha']);
        $totalCreditoOtorgado = (float) $ventasCreditoList->sum('total_venta');
        $creditoVentaIds = $ventasCreditoList->pluck('id')->toArray();

        $totalRecaudado = (float) DB::table('abonos')
            ->whereIn('venta_id', $creditoVentaIds)
            ->sum('abono');

        $saldoPendienteCartera = max(0, $totalCreditoOtorgado - $totalRecaudado);

        $indiceRecuperacion = $totalCreditoOtorgado > 0 
            ? round(($totalRecaudado / $totalCreditoOtorgado) * 100, 2) 
            : 100.0;

        // Tendencia mensual de recuperación de cartera (últimos 6 meses)
        $mesesCartera = [];
        for ($m = 5; $m >= 0; $m--) {
            $startMes = now()->subMonths($m)->startOfMonth();
            $endMes = now()->subMonths($m)->endOfMonth();

            $vCreditoMesQuery = DB::table('ventas')
                ->where('tipo_pago', 'Crédito')
                ->whereBetween('fecha', [$startMes->toDateTimeString(), $endMes->toDateTimeString()]);
            if ($bodegaId) {
                $vCreditoMesQuery->where('bodega_id', $bodegaId);
            }
            $vIds = $vCreditoMesQuery->pluck('id')->toArray();
            $creditoEmitidoMes = (float) $vCreditoMesQuery->sum('total_venta');

            $recaudadoMes = (float) DB::table('abonos')
                ->whereIn('venta_id', $vIds)
                ->sum('abono');

            $mesesCartera[] = [
                'mes' => $startMes->translatedFormat('M Y') ?: $startMes->format('M Y'),
                'credito' => $creditoEmitidoMes,
                'recaudado' => $recaudadoMes,
            ];
        }

        // -------------------------------------------------------------
        // KPI 4: RENDIMIENTO DE VENTAS POR PROVINCIA
        // -------------------------------------------------------------
        $ventasProvinciaQuery = DB::table('ventas');
        if ($startDate) {
            $ventasProvinciaQuery->where('fecha', '>=', $startDate);
        }
        if ($bodegaId) {
            $ventasProvinciaQuery->where('bodega_id', $bodegaId);
        }

        $rawVentasCiudad = $ventasProvinciaQuery
            ->select('ciudad', DB::raw('SUM(total_venta) as monto_total'), DB::raw('COUNT(*) as num_transacciones'))
            ->groupBy('ciudad')
            ->get();

        $ventasPorProvinciaMap = [];
        $granTotalVentas = 0;

        foreach ($rawVentasCiudad as $item) {
            $ciudadClean = strtolower(trim($item->ciudad ?? ''));
            if (empty($ciudadClean)) {
                $provincia = 'Sin Especificar';
            } else {
                $provincia = $this->provinciasMap[$ciudadClean] ?? ucfirst($ciudadClean);
            }

            if (!isset($ventasPorProvinciaMap[$provincia])) {
                $ventasPorProvinciaMap[$provincia] = [
                    'provincia' => $provincia,
                    'monto_total' => 0,
                    'num_transacciones' => 0,
                ];
            }

            $ventasPorProvinciaMap[$provincia]['monto_total'] += (float) $item->monto_total;
            $ventasPorProvinciaMap[$provincia]['num_transacciones'] += (int) $item->num_transacciones;
            $granTotalVentas += (float) $item->monto_total;
        }

        $ventasPorProvincia = collect(array_values($ventasPorProvinciaMap))
            ->map(function ($item) use ($granTotalVentas) {
                $item['porcentaje'] = $granTotalVentas > 0 
                    ? round(($item['monto_total'] / $granTotalVentas) * 100, 1) 
                    : 0;
                $item['ticket_promedio'] = $item['num_transacciones'] > 0 
                    ? round($item['monto_total'] / $item['num_transacciones'], 2) 
                    : 0;
                return $item;
            })
            ->sortByDesc('monto_total')
            ->values();

        // -------------------------------------------------------------
        // MÉTRICAS RESUMEN SUPERIOR
        // -------------------------------------------------------------
        $totalVentasMontoQuery = DB::table('ventas');
        if ($startDate) $totalVentasMontoQuery->where('fecha', '>=', $startDate);
        if ($bodegaId) $totalVentasMontoQuery->where('bodega_id', $bodegaId);
        $totalVentasMonto = (float) $totalVentasMontoQuery->sum('total_venta');

        $bodegas = Bodega::all();

        return view('kpis.index', compact(
            'periodo',
            'bodegaId',
            'bodegas',
            'topProductosFormatted',
            'semanasDevoluciones',
            'tasaDevolucionGlobal',
            'totalCreditoOtorgado',
            'totalRecaudado',
            'saldoPendienteCartera',
            'indiceRecuperacion',
            'mesesCartera',
            'ventasPorProvincia',
            'granTotalVentas',
            'totalVentasMonto'
        ));
    }
}
