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
    
    $this->authorizeResource(TransaccionProducto::class, 'transaccionProducto'); // ✅ Debe coincidir con la ruta
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
    public function confirmar($codigo)
    {
        try {
            DB::beginTransaction();

            // Buscar la nota
            $nota = TipoNota::with('detalles')->where('codigo', $codigo)->firstOrFail();

            // Crear la transacción sin modificar el stock aún
            TransaccionProducto::create([
                'tipo_nota_id' => $nota->codigo,
                'estado' => 'PENDIENTE',
            ]);

            DB::commit();
            return redirect()->route('tipoNota.index')->with('success', 'Nota confirmada. Ahora debes finalizar la transacción.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al confirmar la nota: ' . $e->getMessage());
        }
    }

    /**
     * Finaliza la transacción y ACTUALIZA el stock
     */
    public function finalizar($id)
    {
        try {
            DB::beginTransaction();

            // 🔹 Buscar la transacción
            $transaccion = TransaccionProducto::findOrFail($id);
            $nota = $transaccion->tipoNota;

            // 🔹 Buscar los detalles asociados a la nota
            $detalles = DetalleTipoNota::where('tipo_nota_id', $nota->codigo)->get();

            foreach ($detalles as $detalle) {
                $producto = Producto::where('codigo', $detalle->codigoproducto)->firstOrFail();

                if ($nota->tiponota === 'ENVIO') {
                    // Actualiza el stock
                    if ($producto->cantidad < $detalle->cantidad) {
                        DB::rollBack();
                        return redirect()->back()->with('error', "Stock insuficiente para el producto: {$producto->nombre}.");
                    }
                    $producto->cantidad -= $detalle->cantidad;
                    $producto->save();

                    // Registra el movimiento en la tabla pivote
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
                    // Actualiza el stock
                    $producto->cantidad += $detalle->cantidad;
                    $producto->save();

                    // Registra el movimiento como devolución
                    DB::table('productos_bodega')->insert([
                        'bodega_id'    => $nota->idbodega,
                        'producto_id'  => $producto->codigo,
                        'cantidad'     => $detalle->cantidad,
                        'fecha'        => now(),
                        'es_devolucion'=> true,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                }
            }

            // 🔹 Marcar la transacción como finalizada
            $transaccion->estado = 'FINALIZADA';
            $transaccion->save();

            DB::commit();
            return redirect()->route('transaccionProducto.index')->with('success', 'Transacción finalizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al finalizar la transacción: ' . $e->getMessage());
        }
    }


}
