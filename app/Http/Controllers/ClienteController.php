<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Helpers\AuditoriaHelper;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $clientes = Cliente::query()
            ->when($search, function ($query, $search) {
                return $query->where('nombres', 'ilike', "%{$search}%")
                    ->orWhere('apellidos', 'ilike', "%{$search}%")
                    ->orWhere('cedula', 'like', "%{$search}%")
                    ->orWhere('nombre_empresa', 'ilike', "%{$search}%")
                    ->orWhere('celular', 'like', "%{$search}%");
            })
            ->withCount('ventas')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('clientes.index', compact('clientes', 'search'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    private function validarIdentificacion(string $cedula): ?string
    {
        $num = trim($cedula);
        if (!preg_match('/^\d{10}(\d{3})?$/', $num)) {
            return 'La identificación debe contener 10 dígitos numéricos para Cédula o 13 dígitos para RUC.';
        }
        return null;
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres'        => 'required|string|max:100',
            'apellidos'      => 'required|string|max:100',
            'cedula'         => 'required|string|min:10|max:13|unique:clientes,cedula',
            'direccion'      => 'required|string|max:255',
            'correo'         => 'nullable|email|max:150',
            'celular'        => 'required|string|max:15',
            'nombre_empresa' => 'nullable|string|max:200',
        ]);

        if ($errorId = $this->validarIdentificacion($request->cedula)) {
            return back()->withInput()->with('error', $errorId)->withErrors(['cedula' => $errorId]);
        }

        try {
            $cliente = Cliente::create([
                'nombres'        => trim($request->nombres),
                'apellidos'      => trim($request->apellidos),
                'cedula'         => trim($request->cedula),
                'direccion'      => trim($request->direccion),
                'correo'         => $request->correo ? trim(strtolower($request->correo)) : null,
                'celular'        => trim($request->celular),
                'nombre_empresa' => $request->nombre_empresa ? trim($request->nombre_empresa) : null,
            ]);

            AuditoriaHelper::registrar(
                'Clientes',
                'Creación',
                "Registró al nuevo cliente '{$cliente->nombre_completo}' con identificación '{$cliente->cedula}'"
            );

            return redirect()->route('clientes.index')->with('success', 'Cliente registrado exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Ocurrió un error al guardar el cliente: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $cliente = Cliente::with(['ventas.bodega', 'ventas.detalles'])->findOrFail($id);
        $totalComprado = $cliente->ventas->sum('total_venta');
        
        return view('clientes.show', compact('cliente', 'totalComprado'));
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'nombres'        => 'required|string|max:100',
            'apellidos'      => 'required|string|max:100',
            'cedula'         => ['required', 'string', 'min:10', 'max:13', Rule::unique('clientes', 'cedula')->ignore($cliente->id)],
            'direccion'      => 'required|string|max:255',
            'correo'         => 'nullable|email|max:150',
            'celular'        => 'required|string|max:15',
            'nombre_empresa' => 'nullable|string|max:200',
        ]);

        if ($errorId = $this->validarIdentificacion($request->cedula)) {
            return back()->withInput()->with('error', $errorId)->withErrors(['cedula' => $errorId]);
        }

        try {
            $cliente->update([
                'nombres'        => trim($request->nombres),
                'apellidos'      => trim($request->apellidos),
                'cedula'         => trim($request->cedula),
                'direccion'      => trim($request->direccion),
                'correo'         => $request->correo ? trim(strtolower($request->correo)) : null,
                'celular'        => trim($request->celular),
                'nombre_empresa' => $request->nombre_empresa ? trim($request->nombre_empresa) : null,
            ]);

            AuditoriaHelper::registrar(
                'Clientes',
                'Edición',
                "Actualizó los datos del cliente '{$cliente->nombre_completo}' (ID: {$cliente->id})"
            );

            return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Ocurrió un error al actualizar el cliente: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);

        if ($cliente->ventas()->exists()) {
            return redirect()->route('clientes.index')->with('error', 'No se puede eliminar el cliente porque tiene ventas asociadas en el sistema.');
        }

        $nombre = $cliente->nombre_completo;
        $cedula = $cliente->cedula;

        $cliente->delete();

        AuditoriaHelper::registrar(
            'Clientes',
            'Eliminación',
            "Eliminó al cliente '{$nombre}' (Identificación: {$cedula})"
        );

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }

    /**
     * Endpoint API para búsqueda rápida de clientes (Select2 o Autocomplete)
     */
    public function buscar(Request $request)
    {
        $q = $request->input('q', '');
        $clientes = Cliente::query()
            ->when($q, function ($query, $q) {
                return $query->where('nombres', 'ilike', "%{$q}%")
                    ->orWhere('apellidos', 'ilike', "%{$q}%")
                    ->orWhere('cedula', 'like', "%{$q}%")
                    ->orWhere('nombre_empresa', 'ilike', "%{$q}%");
            })
            ->limit(20)
            ->get(['id', 'nombres', 'apellidos', 'cedula', 'nombre_empresa', 'celular', 'direccion']);

        return response()->json($clientes);
    }
}

