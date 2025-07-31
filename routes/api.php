<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Ruta para login y obtener el token JWT
Route::post('/api', [AuthController::class, 'api']);

// Ruta protegida que requiere autenticación con JWT
Route::middleware('auth:api')->get('/users', [AuthController::class, 'users']);
