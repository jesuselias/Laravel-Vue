<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HotelsController;
use App\Http\Controllers\HubController;
use App\Http\Controllers\HotelLegsController;

use Laravel\Sanctum\Sanctum;

Route::get('/test', function () {
    return response()->json([
        'message' => 'Prueba exitosa',
        'data' => 'Hola desde la ruta de prueba',
    ]);
});

Route::post('/hotel-legs/search', [HotelLegsController::class, 'search']);

Route::get('/hotels', [HotelsController::class, 'index']);

Route::post('/hub/search', [HubController::class, 'search']);




