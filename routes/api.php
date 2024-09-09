<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

/**
 * Ruta protegida por autenticación API
 */
Route::middleware(['auth:api'])->get('/api/user', function (Request $request) {
    return $request->user();
});

/**
 * Ruta pública para búsqueda
 */
Route::get('/api/search', [SearchController::class, 'index']);

/**
 * Ruta de prueba para API
 */
Route::get('/api/test', function () {
    return 'API Test';
});
