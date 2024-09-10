<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Response;

class Controller extends \Illuminate\Routing\Controller
{
    protected $defaultResponse = [
        'status' => null,
        'data' => null,
        'message' => null,
    ];

    public function index()
    {
        $response = [
            'status' => 'success',
            'message' => 'Hola desde el controlador',
            'data' => ['timestamp' => now()->timestamp]
        ];

        return Response::json($response);
    }
}

