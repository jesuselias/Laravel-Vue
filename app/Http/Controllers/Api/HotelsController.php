<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Hotel;

class HotelsController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $hotels = Hotel::all();

        return response()->json([
            'message' => 'Lista de hoteles',
            'data' => $hotels->pluck('name')->toArray(),
        ], 200);
    }
}
