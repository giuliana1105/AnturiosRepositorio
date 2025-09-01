<?php

namespace App\Http\Controllers;

use App\Models\TransaccionProducto;
use App\Models\TipoNota;
use App\Models\Producto;
use App\Models\DetalleTipoNota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Asegúrate de importar esto

class TransaccionProductoController extends Controller
{

    use AuthorizesRequests; 
    public function __construct()
{
    
    //$this->authorizeResource(TransaccionProducto::class, 'transaccionProducto'); // ✅ Debe coincidir con la ruta
}

    /**
     * Lista todas las transacciones
     */
    public function index(Request $request)
    {
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
     * Confirma la nota, pero NO modifica el stock.
     */
    // public function confirmar($codigo)
    // {
    //     try {
    //         DB::beginTransaction();

    //         // Buscar la nota
    //         $nota = TipoNota::with('detalles')->where('codigo', $codigo)->firstOrFail();

    //         // Crear la transacción sin modificar el stock aún
    //         TransaccionProducto::create([
    //             'tipo_nota_id' => $nota->codigo,
    //             'estado' => 'PENDIENTE',
    //         ]);

    //         DB::commit();
    //         return redirect()->route('tipoNota.index')->with('success', 'Nota confirmada. Ahora debes finalizar la transacción.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Error al confirmar la nota: ' . $e->getMessage());
    //     }
    // }
/**
 * Confirma la nota y EJECUTA los movimientos de inventario.
 */
// public function confirmar($codigo)
// {
//     try {
//         DB::beginTransaction();

//         // Buscar la nota con sus detalles
//         $nota = TipoNota::with('detalles')->where('codigo', $codigo)->firstOrFail();
        
//         // Verificar que no esté ya confirmada
//         $transaccionExistente = TransaccionProducto::where('tipo_nota_id', $nota->codigo)->first();
//         if ($transaccionExistente) {
//             return redirect()->back()->with('error', 'Esta nota ya está confirmada.');
//         }

//         // Realizar los movimientos de inventario AQUÍ (no en finalizar)
//         foreach ($nota->detalles as $detalle) {
//             $producto = \App\Models\Producto::where('codigo', $detalle->codigoproducto)->first();
//             if (!$producto) {
//                 throw new \Exception("El producto con código {$detalle->codigoproducto} no existe.");
//             }

//             $cantidad = $detalle->cantidad;

//             if ($nota->tiponota === 'ENVIO') {
//                 // ENVÍO: De bodega MASTER (tabla productos) a bodega específica
                
//                 // Verificar stock disponible nuevamente
//                 if ($producto->cantidad < $cantidad) {
//                     throw new \Exception("Stock insuficiente para el producto {$producto->nombre}. Disponible: {$producto->cantidad}");
//                 }
                
//                 // 1. Restar del stock general (tabla productos = bodega MASTER)
//                 DB::table('productos')
//                     ->where('codigo', $detalle->codigoproducto)
//                     ->decrement('cantidad', $cantidad);

//                 // 2. Registrar entrada en bodega destino
//                 DB::table('productos_bodega')->insert([
//                     'bodega_id' => $nota->idbodega,
//                     'producto_id' => $detalle->codigoproducto,
//                     'cantidad' => $cantidad,
//                     'fecha' => now(),
//                     'es_devolucion' => false,
//                     'created_at' => now(),
//                     'updated_at' => now(),
//                 ]);
                
//             } elseif ($nota->tiponota === 'DEVOLUCION') {
//                 // DEVOLUCIÓN: De bodega específica a bodega MASTER
                
//                 // Verificar stock en bodega nuevamente
//                 $stockBodega = DB::table('productos_bodega')
//                     ->where('bodega_id', $nota->idbodega)
//                     ->where('producto_id', $detalle->codigoproducto)
//                     ->selectRaw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as stock')
//                     ->value('stock') ?? 0;

//                 if ($stockBodega < $cantidad) {
//                     throw new \Exception("Stock insuficiente en bodega para el producto {$producto->nombre}. Disponible: {$stockBodega}");
//                 }
                
//                 // 1. Registrar salida de bodega origen
//                 DB::table('productos_bodega')->insert([
//                     'bodega_id' => $nota->idbodega,
//                     'producto_id' => $detalle->codigoproducto,
//                     'cantidad' => $cantidad,
//                     'fecha' => now(),
//                     'es_devolucion' => true,
//                     'created_at' => now(),
//                     'updated_at' => now(),
//                 ]);

//                 // 2. Sumar al stock general (tabla productos = bodega MASTER)
//                 DB::table('productos')
//                     ->where('codigo', $detalle->codigoproducto)
//                     ->increment('cantidad', $cantidad);
//             }
//         }

//         // Crear la transacción como FINALIZADA directamente
//         TransaccionProducto::create([
//             'tipo_nota_id' => $nota->codigo,
//             'estado' => 'FINALIZADA',
//         ]);

//         DB::commit();
//         return redirect()->route('tipoNota.index')->with('success', 'Nota confirmada y transacción procesada exitosamente.');
        
//     } catch (\Exception $e) {
//         DB::rollBack();
//         Log::error('Error al confirmar nota: ' . $e->getMessage(), [
//             'codigo_nota' => $codigo,
//             'stack_trace' => $e->getTraceAsString()
//         ]);
//         return redirect()->back()->with('error', 'Error al confirmar la nota: ' . $e->getMessage());
//     }
// }


/**
 * Confirma la nota, pero NO modifica el stock.
 */
public function confirmar($codigo)
{
    try {
        DB::beginTransaction();

        // Buscar la nota
        $nota = TipoNota::with('detalles')->where('codigo', $codigo)->firstOrFail();
        
        // Verificar que no esté ya confirmada
        $transaccionExistente = TransaccionProducto::where('tipo_nota_id', $nota->codigo)->first();
        if ($transaccionExistente) {
            return redirect()->back()->with('error', 'Esta nota ya está confirmada.');
        }

        // Crear la transacción sin modificar el stock aún
        TransaccionProducto::create([
            'tipo_nota_id' => $nota->codigo,
            'estado' => 'PENDIENTE',
        ]);

        DB::commit();
        return redirect()->route('tipoNota.index')->with('success', 'Nota confirmada. Ahora debe finalizar la transacción para procesar el inventario.');
        
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error al confirmar la nota: ' . $e->getMessage());
    }
}
/**
     * Finaliza la transacción y ACTUALIZA el stock
     */
    // public function finalizar($id)
    // {
    //     try {
    //         DB::beginTransaction();

    //         // 🔹 Buscar la transacción
    //         $transaccion = TransaccionProducto::findOrFail($id);
    //         $nota = $transaccion->tipoNota;

    //         // 🔹 Buscar los detalles asociados a la nota
    //         $detalles = DetalleTipoNota::where('tipo_nota_id', $nota->codigo)->get();

    //         foreach ($detalles as $detalle) {
    //             $producto = Producto::where('codigo', $detalle->codigoproducto)->firstOrFail();

    //             if ($nota->tiponota === 'ENVIO') {
    //                 // Actualiza el stock
    //                 if ($producto->cantidad < $detalle->cantidad) {
    //                     DB::rollBack();
    //                     return redirect()->back()->with('error', "Stock insuficiente para el producto: {$producto->nombre}.");
    //                 }
    //                 $producto->cantidad -= $detalle->cantidad;
    //                 $producto->save();

    //                 // Registra el movimiento en la tabla pivote
    //                 DB::table('productos_bodega')->insert([
    //                     'bodega_id'    => $nota->idbodega,
    //                     'producto_id'  => $producto->codigo,
    //                     'cantidad'     => $detalle->cantidad,
    //                     'fecha'        => now(),
    //                     'es_devolucion'=> false,
    //                     'created_at'   => now(),
    //                     'updated_at'   => now(),
    //                 ]);
    //             } elseif ($nota->tiponota === 'DEVOLUCION') {
    //                 // Actualiza el stock
    //                 $producto->cantidad += $detalle->cantidad;
    //                 $producto->save();

    //                 // Registra el movimiento como devolución
    //                 DB::table('productos_bodega')->insert([
    //                     'bodega_id'    => $nota->idbodega,
    //                     'producto_id'  => $producto->codigo,
    //                     'cantidad'     => $detalle->cantidad,
    //                     'fecha'        => now(),
    //                     'es_devolucion'=> true,
    //                     'created_at'   => now(),
    //                     'updated_at'   => now(),
    //                 ]);
    //             }
    //         }

    //         // 🔹 Marcar la transacción como finalizada
    //         $transaccion->estado = 'FINALIZADA';
    //         $transaccion->save();

    //         DB::commit();
    //         return redirect()->route('transaccionProducto.index')->with('success', 'Transacción finalizada correctamente.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Error al finalizar la transacción: ' . $e->getMessage());
    //     }
    // }

/**
 * Finaliza la transacción (Ya no modifica stock - solo cambia estado si es necesario)
 * Este método podría eliminarse si no lo necesitas, ya que ahora todo se hace en confirmar()
 */
// public function finalizar($id)
// {
//     try {
//         DB::beginTransaction();

//         // Buscar la transacción
//         $transaccion = TransaccionProducto::findOrFail($id);
        
//         // Verificar que no esté ya finalizada
//         if ($transaccion->estado === 'FINALIZADA') {
//             return redirect()->back()->with('info', 'Esta transacción ya está finalizada.');
//         }

//         // Solo cambiar el estado (los movimientos de inventario ya se hicieron en confirmar())
//         $transaccion->estado = 'FINALIZADA';
//         $transaccion->save();

//         DB::commit();
//         return redirect()->route('transaccionProducto.index')->with('success', 'Transacción finalizada correctamente.');
        
//     } catch (\Exception $e) {
//         DB::rollBack();
//         Log::error('Error al finalizar transacción: ' . $e->getMessage(), [
//             'transaccion_id' => $id,
//             'stack_trace' => $e->getTraceAsString()
//         ]);
//         return redirect()->back()->with('error', 'Error al finalizar la transacción: ' . $e->getMessage());
//     }
// }
/**
 * Finaliza la transacción y ACTUALIZA el stock
 */
public function finalizar($id)
{
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

        foreach ($detalles as $detalle) {
            $producto = Producto::where('codigo', $detalle->codigoproducto)->firstOrFail();

            if ($nota->tiponota === 'ENVIO') {
                // Verificar stock disponible
                if ($producto->cantidad < $detalle->cantidad) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Stock insuficiente para el producto: {$producto->nombre}. Disponible: {$producto->cantidad}");
                }
                
                // 1. Restar del stock general (tabla productos = bodega MASTER)
                $producto->cantidad -= $detalle->cantidad;
                $producto->save();

                // 2. Registrar entrada en bodega destino
                DB::table('productos_bodega')->insert([
                    'bodega_id'    => $nota->idbodega,
                    'producto_id'  => $producto->codigo,
                    'cantidad'     => $detalle->cantidad,
                    'fecha'        => now(),
                    'es_devolucion'=> false,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
                
            } elseif ($nota->tiponota === 'DEVOLUCION') {
                // Verificar stock en bodega
                $stockBodega = DB::table('productos_bodega')
                    ->where('bodega_id', $nota->idbodega)
                    ->where('producto_id', $detalle->codigoproducto)
                    ->selectRaw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as stock')
                    ->value('stock') ?? 0;

                if ($stockBodega < $detalle->cantidad) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Stock insuficiente en bodega para el producto: {$producto->nombre}. Disponible: {$stockBodega}");
                }
                
                // 1. Registrar salida de bodega origen
                DB::table('productos_bodega')->insert([
                    'bodega_id'    => $nota->idbodega,
                    'producto_id'  => $producto->codigo,
                    'cantidad'     => $detalle->cantidad,
                    'fecha'        => now(),
                    'es_devolucion'=> true,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                // 2. Sumar al stock general (tabla productos = bodega MASTER)
                $producto->cantidad += $detalle->cantidad;
                $producto->save();
            }
        }

        // Marcar la transacción como finalizada
        $transaccion->estado = 'FINALIZADA';
        $transaccion->save();

        DB::commit();
        return redirect()->route('transaccionProducto.index')->with('success', 'Transacción finalizada correctamente. Inventario actualizado.');
        
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error al finalizar la transacción: ' . $e->getMessage());
    }
}
}
