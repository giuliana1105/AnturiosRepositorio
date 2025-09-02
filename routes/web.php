<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\TipoIdentificacionController;
use App\Http\Controllers\BodegaController;
use App\Http\Controllers\TransaccionProductoController;
use App\Http\Controllers\TipoEmpaquesController;
use App\Http\Controllers\TipoNotaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\VentaBodegaController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;


Route::middleware(['role:super-admin'])->group(function() {
    // Rutas protegidas
});


// 🔹 Ruta para la página de inicio de sesión
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// 🔹 Ruta para procesar el inicio de sesión
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// 🔹 Ruta para cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 🔹 Grupo de rutas protegidas (Solo usuarios autenticados pueden acceder)
Route::middleware(['auth'])->group(function () {

    // 🔹 Ruta para la vista principal (home)
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/home/bodega/{id}', [App\Http\Controllers\HomeController::class, 'bodega'])->name('home.bodega');

    Route::get('/home/master', [App\Http\Controllers\HomeController::class, 'master'])->name('home.master');

    // 🔹 Rutas para los módulos principales
    //Route::resource('producto', ProductoController::class);
    Route::resource('productos', ProductoController::class)->parameters([
        'producto' => 'codigo' // Define que 'producto' usa 'cod_Prod' como identificador
    ]);
    Route::post('productos/import', [ProductoController::class, 'import'])->name('productos.import'); // <-- CORREGIDO
    
    Route::resource('roles', RoleController::class);

    Route::resource('empleados', EmpleadoController::class);
    Route::post('empleados/import', [EmpleadoController::class, 'import'])->name('empleados.import'); // <-- OPCIONAL, para mantener consistencia

    Route::resource('bodegas', BodegaController::class);
    Route::resource('tipoNota', TipoNotaController::class);
    Route::resource('users', UserController::class);


    // ✅ Ruta para confirmar una Nota y crear una transacción
    Route::post('/tipoNota/confirmar/{codigo}', [TransaccionProductoController::class, 'confirmar'])
        ->name('tipoNota.confirmar');

    // ✅ Rutas para Transacción Producto
    Route::get('/transaccionProducto', [TransaccionProductoController::class, 'index'])
        ->name('transaccionProducto.index');

    Route::post('/transaccionProducto/confirmar/{codigo}', [TransaccionProductoController::class, 'confirmar'])
        ->name('transaccionProducto.confirmar');

    // ✅ Cambiado a POST para corregir el error "Method Not Allowed"
    Route::post('/transaccionProducto/finalizar/{id}', [TransaccionProductoController::class, 'finalizar'])
        ->name('transaccionProducto.finalizar');

    // ✅ Generar PDF de Tipo Nota
    Route::get('tipoNota/pdf/{codigo}', [TipoNotaController::class, 'generarPDF'])->name('tipoNota.pdf');

    // Para ENVÍO
    Route::get('/bodegas/master/productos', [TipoNotaController::class, 'productosMaster']);
    // Para DEVOLUCIÓN
    Route::get('/bodegas/{id}/productos', [TipoNotaController::class, 'productosPorBodega']);

    Route::get('/bodega/{id}/venta', [VentaBodegaController::class, 'create'])->name('venta.create');
    Route::post('/bodega/{id}/venta', [VentaBodegaController::class, 'store'])->name('venta.store');

    Route::get('/ventas', [VentaBodegaController::class, 'index'])->name('venta.index');

    Route::get('/bodega/{id}', [BodegaController::class, 'show'])->name('bodega.show');
    Route::get('venta/{venta}', [App\Http\Controllers\VentaBodegaController::class, 'show'])->name('venta.show');
    Route::get('venta/{venta}/abono', [App\Http\Controllers\VentaBodegaController::class, 'abonoForm'])->name('venta.abono');
    Route::post('venta/{venta}/abono', [App\Http\Controllers\VentaBodegaController::class, 'agregarAbono'])->name('venta.abono.store');
    Route::get('venta/{venta}/edit', [App\Http\Controllers\VentaBodegaController::class, 'edit'])->name('venta.edit');
    Route::put('venta/{venta}', [App\Http\Controllers\VentaBodegaController::class, 'update'])->name('venta.update');
    Route::delete('venta/{venta}', [App\Http\Controllers\VentaBodegaController::class, 'destroy'])->name('venta.destroy');

    Route::get('bodega/{bodega}/ventas', [VentaBodegaController::class, 'indexPorBodega'])->name('venta.index.bodega');

    Route::get('ventas/exportar', [VentaBodegaController::class, 'exportarVentas'])->name('ventas.exportar');

    Route::get('/password/change', [AuthController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/password/change', [AuthController::class, 'changePassword'])->name('password.change');

    Route::get('bodega/{id}/stock/pdf', [BodegaController::class, 'stockPdf'])->name('bodega.stock.pdf');
});

// Password Reset Routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::post('/empleados/{nro_identificacion}/reset-password', [\App\Http\Controllers\EmpleadoController::class, 'resetPassword'])->name('empleados.reset_password');


// 🔹 Redirigir la raíz al login si no está autenticado
Route::get('/', function () {
    return redirect()->route('login');
});
