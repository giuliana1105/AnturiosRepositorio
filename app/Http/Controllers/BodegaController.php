<?php

namespace App\Http\Controllers;

use App\Models\Bodega;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Asegúrate de importar esto

class BodegaController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {  
        $this->authorizeResource(Bodega::class, 'bodega'); // ✅ Debe coincidir con la ruta
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bodegas = Bodega::orderBy('nombrebodega', 'ASC')->paginate(5);
        return view('bodegas.index', compact('bodegas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bodegas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Ya no es necesario validar el idbodega porque es autoincremental
        $request->validate([
            'nombrebodega' => 'required|max:10',  // Solo validamos el nombre
        ]);

        Bodega::create($request->all());  // Aquí idbodega será asignado automáticamente
        return redirect()->route('bodegas.index')->with('success', 'Registro creado satisfactoriamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $idbodega)
    {
        $bodega = Bodega::findOrFail($idbodega);
        return view('bodegas.show', compact('bodega'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $idbodega)
    {
        $bodega = Bodega::findOrFail($idbodega);
        return view('bodegas.edit', compact('bodega'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $idbodega)
    {
        $request->validate([
            'nombrebodega' => 'required|max:10',  // Solo validamos el nombre
        ]);

        // No es necesario enviar 'idbodega' porque será un campo autoincremental
        $bodega = Bodega::findOrFail($idbodega);
        $bodega->update($request->all());
        
        return redirect()->route('bodegas.index')->with('success', 'Registro actualizado satisfactoriamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $idbodega)
    {
        Bodega::findOrFail($idbodega)->delete();
        return redirect()->route('bodegas.index')->with('success', 'Registro eliminado satisfactoriamente');
    }
}
