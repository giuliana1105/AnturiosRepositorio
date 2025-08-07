<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bodega;
use App\Models\Producto;
use App\Models\Empleado;
use App\Models\TipoNota;
use App\Models\TransaccionProducto;

class HomeController extends Controller
{
    public function index()
    {
        $bodegas = Bodega::all();
        return view('home', compact('bodegas'));
    }

    public function master()
    {
        $productos = Producto::all();
        $empleados = Empleado::all();
        $bodegas = Bodega::all();
        $tiposNota = TipoNota::all();
        $transacciones = TransaccionProducto::all();
        return view('home.master', compact('productos', 'empleados', 'bodegas', 'tiposNota', 'transacciones'));
    }

    public function bodega($id)
    {
        $bodega = Bodega::findOrFail($id);
        $productos = $bodega->productosEnviados()->with('producto')->get();
        return view('home.bodega', compact('bodega', 'productos'));
    }
}