<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\HubService;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class HubController extends Controller
{
    protected $hubService;

    public function __construct(HubService $hubService)
    {
        $this->hubService = $hubService;
    }

    public function search(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'hotelId' => 'required|integer',
                'checkIn' => 'required|date_format:Y-m-d',
                'checkOut' => 'required|date_format:Y-m-d',
                'numberOfGuests' => 'required|integer',
                'numberOfRooms' => 'required|integer',
                'currency' => 'required|string|max:3'
            ]);

            $results = $this->hubService->search($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Búsqueda exitosa',
                'data' => $results
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de entrada inválidos',
                'errors' => $e->errors(),
                'code' => 422
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error en HubController.search: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error durante la búsqueda',
                'error' => $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }
}
