<?php

namespace App\Http\Controllers;

use App\Models\TransaccionProducto;
use App\Models\TipoNota;
use App\Models\Producto;
use App\Models\DetalleTipoNota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TransaccionProductoController extends Controller
{
    use AuthorizesRequests; 

    public function __construct()
    {
        //$this->authorizeResource(TransaccionProducto::class, 'transaccionProducto');
    }

    /**
     * Lista todas las transacciones
     */
    public function index(Request $request)
    {
        $cargo = auth()->user()->cargoNombre();
        if (in_array($cargo, ['Vendedor', 'Vendedor camión'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $search = $request->input('search');
        $estado = $request->input('estado');

        $query = TransaccionProducto::with('tipoNota.detalles.producto');

        if ($estado) {
            $query->where('estado', $estado);
        }

        if ($search) {
            $query->whereHas('tipoNota', function ($q) use ($search) {
                $q->where('codigo', 'LIKE', "%$search%");
            });
        }

        $pendientes = TransaccionProducto::where('estado', 'PENDIENTE')->count();
        $finalizadas = TransaccionProducto::where('estado', 'FINALIZADA')->count();

        $transacciones = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('transaccionProducto.index', compact('transacciones', 'pendientes', 'finalizadas', 'search', 'estado'));
    }

    /**
     * PASO 1: Confirma la nota creando una transacción en estado PENDIENTE
     * 🔹 NO modifica inventarios
     * 🔹 NO actualiza stock
     * 🔹 Solo registra la intención de procesar la nota
     */
    public function confirmar($codigo)
    {
        $cargo = auth()->user()->cargoNombre();
        if (in_array($cargo, ['Jefe de bodega'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        try {
            DB::beginTransaction();

            // Buscar la nota
            $nota = TipoNota::with('detalles')->where('codigo', $codigo)->firstOrFail();
            
            // Verificar que no esté ya confirmada
            $transaccionExistente = TransaccionProducto::where('tipo_nota_id', $nota->codigo)->first();
            if ($transaccionExistente) {
                return redirect()->back()->with('error', 'Esta nota ya está confirmada.');
            }

            // 🔹 SOLO crear la transacción - SIN TOCAR INVENTARIOS
            TransaccionProducto::create([
                'tipo_nota_id' => $nota->codigo,
                'estado' => 'PENDIENTE',
            ]);

            // ⚠️ IMPORTANTE: No se modifica ningún stock aquí
            // ⚠️ No se registran movimientos en productos_bodega
            // ⚠️ Todo el manejo de inventario se hace en finalizar()

            DB::commit();
            return redirect()->route('tipoNota.index')->with('success', 'Nota confirmada en estado PENDIENTE. Use "Finalizar" para procesar el inventario.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al confirmar la nota: ' . $e->getMessage());
        }
    }

    /**
     * PASO 2: Finaliza la transacción y ACTUALIZA todo el inventario
     * 🔹 Verifica stock disponible
     * 🔹 Actualiza tabla productos (stock general/master)
     * 🔹 Registra movimientos en productos_bodega
     * 🔹 Cambia estado a FINALIZADA
     */
    public function finalizar($id)
    {
        $cargo = auth()->user()->cargoNombre();
        if (in_array($cargo, ['Vendedor', 'Vendedor camión'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        try {
            DB::beginTransaction();

            // Buscar la transacción
            $transaccion = TransaccionProducto::findOrFail($id);
            $nota = $transaccion->tipoNota;

            // Verificar que esté en estado PENDIENTE
            if ($transaccion->estado !== 'PENDIENTE') {
                return redirect()->back()->with('error', 'Esta transacción ya está finalizada o no está pendiente.');
            }

            // Buscar los detalles asociados a la nota
            $detalles = DetalleTipoNota::where('tipo_nota_id', $nota->codigo)->get();

            // 🔹 VERIFICAR STOCK ANTES DE PROCESAR PARA TODOS LOS PRODUCTOS
            $hayStockDeAlgo = false;
            $hayFaltantes = false;
            
            $detallesAProcesar = [];

            foreach ($detalles as $detalle) {
                $producto = Producto::where('codigo', $detalle->codigoproducto)->first();
                
                $stockDisponible = 0;
                if ($nota->tiponota === 'ENVIO') {
                    $stockDisponible = $producto ? $producto->cantidad : 0;
                } elseif ($nota->tiponota === 'DEVOLUCION') {
                    $stockDisponible = DB::table('productos_bodega')
                        ->where('bodega_id', $nota->idbodega)
                        ->where('producto_id', $detalle->codigoproducto)
                        ->selectRaw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as stock')
                        ->value('stock') ?? 0;
                }

                $stockEnviar = min($detalle->cantidad, max(0, $stockDisponible));

                if ($stockEnviar < $detalle->cantidad) {
                    $hayFaltantes = true;
                }

                if ($stockEnviar > 0) {
                    $hayStockDeAlgo = true;
                    $detallesAProcesar[] = [
                        'producto' => $producto,
                        'cantidad' => $stockEnviar,
                        'original' => $detalle
                    ];
                }
            }
            
            // Recibir parametro forceReject por si el admin decidió rechazar todo desde el modal
            $forceReject = request()->boolean('force_reject');
            
            if (!$hayStockDeAlgo || $forceReject) {
                // No hay stock de nada o el admin decidió rechazar la nota por completo
                $transaccion->estado = 'RECHAZADA';
                $transaccion->save();
                DB::commit();
                return redirect()->route('transaccionProducto.index')->with('error', 'La transacción fue RECHAZADA por falta de stock total o decisión del administrador.');
            }

            // 🔹 PROCESAR INVENTARIOS SOLO PARA LAS CANTIDADES DISPONIBLES
            foreach ($detallesAProcesar as $item) {
                $producto = $item['producto'];
                $cantidad = $item['cantidad'];
                
                $item['original']->cantidad = $cantidad;
                $item['original']->save();

                if ($nota->tiponota === 'ENVIO') {
                    // 🔹 RESTAR del stock general (tabla productos = bodega MASTER)
                    $producto->cantidad -= $cantidad;
                    $producto->save();

                    // 🔹 REGISTRAR entrada en bodega destino
                    DB::table('productos_bodega')->insert([
                        'bodega_id'    => $nota->idbodega,
                        'producto_id'  => $producto->codigo,
                        'cantidad'     => $cantidad,
                        'fecha'        => now(),
                        'es_devolucion'=> false,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                    
                } elseif ($nota->tiponota === 'DEVOLUCION') {
                    // 🔹 REGISTRAR salida de bodega origen (es_devolucion = true)
                    DB::table('productos_bodega')->insert([
                        'bodega_id'    => $nota->idbodega,
                        'producto_id'  => $producto->codigo,
                        'cantidad'     => $cantidad,
                        'fecha'        => now(),
                        'es_devolucion'=> true,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);

                    // 🔹 SUMAR al stock general (tabla productos = bodega MASTER)
                    $producto->cantidad += $cantidad;
                    $producto->save();
                }
            }
            
            // Eliminar los detalles que quedaron con cantidad 0 para que no salgan en reportes o vistas
            DetalleTipoNota::where('tipo_nota_id', $nota->codigo)->where('cantidad', 0)->delete();

            // 🔹 MARCAR transacción como finalizada o parcial
            $transaccion->estado = $hayFaltantes ? 'FINALIZADA_PARCIAL' : 'FINALIZADA';
            $transaccion->save();

            DB::commit();
            $msg = $hayFaltantes ? 'Transacción finalizada PARCIALMENTE. Solo se despachó lo disponible.' : 'Transacción finalizada correctamente. Inventario actualizado.';
            return redirect()->route('transaccionProducto.index')->with('success', $msg);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al finalizar la transacción: ' . $e->getMessage());
        }
    }

    public function verificarStock($id)
    {
        try {
            $transaccion = TransaccionProducto::findOrFail($id);
            $nota = $transaccion->tipoNota;

            $detalles = DetalleTipoNota::where('tipo_nota_id', $nota->codigo)->get();
            $productosFaltantes = [];
            $hayStockDeAlgo = false;

            foreach ($detalles as $detalle) {
                $producto = Producto::where('codigo', $detalle->codigoproducto)->first();
                $nombreProd = $producto ? $producto->nombre : "Producto Cod. {$detalle->codigoproducto}";
                
                $stockDisponible = 0;
                if ($nota->tiponota === 'ENVIO') {
                    $stockDisponible = $producto ? $producto->cantidad : 0;
                } elseif ($nota->tiponota === 'DEVOLUCION') {
                    $stockDisponible = DB::table('productos_bodega')
                        ->where('bodega_id', $nota->idbodega)
                        ->where('producto_id', $detalle->codigoproducto)
                        ->selectRaw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as stock')
                        ->value('stock') ?? 0;
                }

                if ($stockDisponible < $detalle->cantidad) {
                    $productosFaltantes[] = [
                        'nombre' => $nombreProd,
                        'solicitado' => $detalle->cantidad,
                        'disponible' => max(0, $stockDisponible)
                    ];
                }
                
                if ($stockDisponible > 0) {
                    $hayStockDeAlgo = true;
                }
            }

            if (empty($productosFaltantes)) {
                return response()->json(['status' => 'ok']);
            }

            return response()->json([
                'status' => 'insufficient',
                'faltantes' => $productosFaltantes,
                'hay_stock_parcial' => $hayStockDeAlgo
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}