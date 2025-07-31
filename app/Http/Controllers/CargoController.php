<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cargo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CargoController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        // Autorización de recursos (policy)
        $this->authorizeResource(Cargo::class, 'cargo');
    }

    /**
     * Muestra la lista de cargos.
     */
    public function index()
    {
        $cargos = Cargo::orderBy('nombrecargo', 'ASC')->paginate(10);
        return view('cargo.index', compact('cargos'));
    }

    /**
     * Muestra el formulario para crear un nuevo cargo.
     */
    public function create()
    {
        return view('cargo.create');
    }

    /**
     * Almacena un nuevo cargo en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombrecargo' => 'required|string|max:255', // Validación en Laravel
        ]);
    
        try {
            // Crear el cargo
            Cargo::create($request->all());
    
            return redirect()->route('cargo.index')->with('success', 'Cargo creado con éxito.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Captura el error y extrae el mensaje del trigger
            $errorMessage = $e->getMessage();
    
            // Verifica si el error es del trigger
            if (preg_match("/ERROR:  (.*?)\\n/", $errorMessage, $matches)) {
                $errorText = trim($matches[1]); // Extrae el mensaje de error del trigger
            } else {
                $errorText = 'Error al crear el cargo.'; // Mensaje genérico si no es un error del trigger
            }
    
            return redirect()->back()->withInput()->with('error', $errorText);
        }
    }

    /**
     * Muestra el formulario para editar un cargo existente.
     */
    public function edit($codigocargo)
    {
        $cargo = Cargo::where('codigocargo', $codigocargo)->firstOrFail();
        return view('cargo.edit', compact('cargo'));
    }

    /**
     * Actualiza un cargo existente en la base de datos.
     */
    public function update(Request $request, $codigocargo)
    {
        // Validación de campos
        $request->validate([
            'nombrecargo' => 'required|string|max:255', // Solo validamos el nombre
        ]);

        $cargo = Cargo::where('codigocargo', $codigocargo)->firstOrFail();

        try {
            // Actualizar solo el nombre del cargo
            $cargo->update([
                'nombrecargo' => $request->nombrecargo,
            ]);

            return redirect()->route('cargo.index')->with('success', 'Cargo actualizado con éxito.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Captura el error y extrae el mensaje del trigger
            $errorMessage = $e->getMessage();

            if (preg_match("/ERROR:  (.*?)\\n/", $errorMessage, $matches)) {
                $errorText = trim($matches[1]); // Extrae el mensaje de error del trigger
            } else {
                $errorText = 'Error al actualizar el cargo.'; // Mensaje genérico si no es un error del trigger
            }

            return redirect()->back()->withInput()->with('error', $errorText);
        }
    }

    /**
     * Elimina un cargo de la base de datos.
     */
    public function destroy($codigocargo)
    {
        $cargo = Cargo::where('codigocargo', $codigocargo)->firstOrFail();

        try {
            // Eliminar el cargo
            $cargo->delete();

            return redirect()->route('cargo.index')->with('success', 'Cargo eliminado con éxito.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Captura el error y extrae el mensaje del trigger
            $errorMessage = $e->getMessage();

            if (preg_match("/ERROR:  (.*?)\\n/", $errorMessage, $matches)) {
                $errorText = trim($matches[1]); // Extrae el mensaje de error del trigger
            } else {
                $errorText = 'Error al eliminar el cargo.'; // Mensaje genérico si no es un error del trigger
            }

            return redirect()->back()->with('error', $errorText);
        }
    }
}