<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use \App\Http\Controllers\Controller;

class HotelsController extends Controller
{
    public function index()
    {
        $hotels = Hotel::orderBy('id')->get();

        return response()->json([
            'message' => 'Lista de hoteles',
            'data' => $hotels->map(function ($hotel) {
                return [
                    'id' => $hotel->id,
                    'name' => $hotel->name,
                ];
            })
        ], 200);
    }
}
