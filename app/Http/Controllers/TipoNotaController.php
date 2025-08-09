<?php

namespace App\Http\Controllers;

use App\Models\TipoNota;
use App\Models\Empleado;
use App\Models\Bodega;
use App\Models\Producto;
use App\Models\DetalleTipoNota;
use Barryvdh\DomPDF\Facade\Pdf; // ✅ Importación corregida
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
//use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Asegúrate de importar esto

class TipoNotaController extends Controller
{
    //     use AuthorizesRequests;
    //     public function __construct()
    // {

    //     $this->authorizeResource(TipoNota::class, 'tipoNota'); // ✅ Debe coincidir con la ruta
    // }


    /**
     * Muestra la lista de notas.
     */
    public function index()
    {
        $tipoNotas = TipoNota::with(['responsableEmpleado', 'bodega', 'detalles.producto', 'transaccion'])
            ->paginate(10);

        return view('tipoNota.index', compact('tipoNotas'));
    }

    /**
     * Muestra el formulario para crear una nueva nota.
     */
    public function create()
    {
        $empleados = Empleado::all();
        $bodegas = Bodega::all();
        $productos = Producto::all();

        return view('tipoNota.create', compact('empleados', 'bodegas', 'productos'));
    }

    /**
     * Guarda una nueva nota en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tiponota' => 'required|string|max:255',
            'nro_identificacion' => 'required|exists:empleados,nro_identificacion',
            'idbodega' => 'required|string|exists:bodegas,idbodega',
            'codigoproducto' => 'required|array|min:1',
            'cantidad' => 'required|array|min:1',
        ]);

        // Validación para devoluciones: no permitir devolver más de lo que hay en la bodega
        if ($request->tiponota === 'DEVOLUCION') {
            foreach ($request->codigoproducto as $index => $codigo) {
                $stock = DB::table('productos_bodega')
                    ->where('bodega_id', $request->idbodega)
                    ->where('producto_id', $codigo)
                    ->selectRaw('SUM(CASE WHEN es_devolucion = false THEN cantidad ELSE 0 END) - SUM(CASE WHEN es_devolucion = true THEN cantidad ELSE 0 END) as stock')
                    ->value('stock') ?? 0;

                if ($request->cantidad[$index] > $stock) {
                    return redirect()->back()->with('error', 'Cantidad insuficiente para el producto ' . $codigo);
                }
            }
        }

        try {
            DB::beginTransaction();

            $ultimoCodigo = TipoNota::latest('codigo')->first();
            $numero = $ultimoCodigo ? intval(str_replace('TN-', '', $ultimoCodigo->codigo)) + 1 : 1;
            $codigoGenerado = 'TN-' . $numero;

            $nota = TipoNota::create([
                'codigo' => $codigoGenerado,
                'tiponota' => $request->tiponota,
                'nro_identificacion' => $request->nro_identificacion,
                'idbodega' => $request->idbodega,
                'fechanota' => now(),
            ]);

            foreach ($request->codigoproducto as $index => $codigo) {
                // Guarda el detalle de la nota
                DetalleTipoNota::create([
                    'tipo_nota_id' => $nota->codigo,
                    'codigoproducto' => $codigo,
                    'cantidad' => $request->cantidad[$index],
                ]);

                // Guarda el movimiento en productos_bodega
                DB::table('productos_bodega')->insert([
                    'bodega_id' => $request->idbodega,
                    'producto_id' => $codigo, // <-- Debe ser el código del producto, ej: 'PF003'
                    'cantidad' => $request->cantidad[$index],
                    'fecha' => now(),
                    'es_devolucion' => $request->tiponota === 'DEVOLUCION',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('tipoNota.index')->with('success', 'Nota creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al crear la nota: ' . $e->getMessage());
        }
    }

    /**
     * Muestra una nota específica.
     */
    public function show($codigo)
    {
        $tipoNota = TipoNota::with(['responsableEmpleado', 'bodega', 'detalles.producto'])
            ->where('codigo', $codigo)
            ->firstOrFail();

        return view('tipoNota.show', compact('tipoNota'));
    }

    /**
     * Muestra el formulario para editar una nota.
     */
    public function edit($codigo)
    {
        $tipoNota = TipoNota::with('detalles')->where('codigo', $codigo)->firstOrFail();
        $empleados = Empleado::all();
        $bodegas = Bodega::all();
        $productos = Producto::all();

        return view('tipoNota.edit', compact('tipoNota', 'empleados', 'bodegas', 'productos'));
    }

    /**
     * Actualiza una nota en la base de datos.
     */
    public function update(Request $request, $codigo)
    {
        $request->validate([
            'tiponota' => 'required|string|max:255',
            'nro_identificacion' => 'required|exists:empleados,nro_identificacion',
            'idbodega' => 'required|string|exists:bodegas,idbodega',
            'codigoproducto' => 'required|array|min:1',
            'cantidad' => 'required|array|min:1',
        ]);

        try {
            DB::beginTransaction();

            $nota = TipoNota::where('codigo', $codigo)->firstOrFail();
            $nota->update([
                'tiponota' => $request->tiponota,
                'nro_identificacion' => $request->nro_identificacion,
                'idbodega' => $request->idbodega,
            ]);

            $nota->detalles()->delete();

            foreach ($request->codigoproducto as $index => $productoId) {
                DetalleTipoNota::create([
                    'tipo_nota_id' => $nota->codigo,
                    'codigoproducto' => $productoId,
                    'cantidad' => $request->cantidad[$index],
                ]);
            }

            DB::commit();
            return redirect()->route('tipoNota.index')->with('success', 'Nota actualizada correctamente.');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar la nota.');
        }
    }

    /**
     * Elimina una nota.
     */
    public function destroy($codigo)
    {
        try {
            DB::beginTransaction();
            $nota = TipoNota::where('codigo', $codigo)->firstOrFail();
            $nota->detalles()->delete();
            $nota->delete();
            DB::commit();

            return redirect()->route('tipoNota.index')->with('success', 'Nota eliminada correctamente.');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al eliminar la nota.');
        }
    }

    /**
     * Genera un PDF con la información de una nota.
     */

    public function generarPDF($codigo)
    {
        // Buscar la nota por código
        $nota = TipoNota::with(['responsableEmpleado', 'bodega', 'detalles.producto', 'transaccion'])
            ->where('codigo', $codigo)
            ->firstOrFail();

        // Verificar si la transacción existe y está confirmada
        if ($nota->transaccion === null ) {
            return redirect()->back()->with('error', 'La nota debe estar confirmada antes de generar el PDF.');
        }

        // Si la transacción está confirmada, proceder a generar el PDF
        $pdf = Pdf::loadView('tipoNota.pdf', compact('nota'));

        return $pdf->download("Nota_{$nota->codigo}.pdf");
    }
}
