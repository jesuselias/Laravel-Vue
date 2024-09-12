<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\HotelLegsConnector;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class HotelLegsController extends Controller
{
    protected $hotelLegsConnector;

    public function __construct(HotelLegsConnector $hotelLegsConnector)
    {
        $this->hotelLegsConnector = $hotelLegsConnector;
    }

    public function search(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'hotel' => 'required|integer',
                'checkInDate' => 'required|date_format:Y-m-d',
                'numberOfNights' => 'required|integer',
                'guests' => 'required|integer',
                'rooms' => 'required|integer',
                'currency' => 'required|string|max:3'
            ]);

            $results = $this->hotelLegsConnector->search([
                'hotel' => $validatedData['hotel'],
                'checkInDate' => $validatedData['checkInDate'],
                'numberOfNights' => $validatedData['numberOfNights'],
                'guests' => $validatedData['guests'],
                'rooms' => $validatedData['rooms'],
                'currency' => strtoupper($validatedData['currency'])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Búsqueda exitosa',
                'data' => [
                    'results' => $results
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de entrada inválidos',
                'errors' => $e->errors(),
                'code' => 422
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error en HotelLegsController.search: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error durante la búsqueda',
                'error' => $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }
}