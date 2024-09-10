<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\HotelsController;
use App\Http\Controllers\Api\HubController;
use App\Http\Controllers\Api\HotelLegsConnectorController;

use Laravel\Sanctum\Sanctum;

Route::get('/hub/search', [HubController::class, 'search']);
Route::get('/hotel-legs/search', [HotelLegsConnectorController::class, 'search']);

Route::get('/test', function () {
    return response()->json([
        'message' => 'Prueba exitosa',
        'data' => 'Hola desde la ruta de prueba',
    ]);
});

Route::get('/hotels', [HotelsController::class, 'index']);

Route::get('/search', [SearchController::class, 'search']);


