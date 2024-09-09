<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomRate;

class HubController extends Controller
{
    public function search(Request $request)
    {
        $hubRequest = $request->all();

        // Simulación de llamadas a múltiples proveedores
        $providers = ['HotelLegs', 'TravelDoorX', 'Speedia'];
        
        $responses = [];
        foreach ($providers as $provider) {
            $response = $this->translateToProvider($hubRequest, $provider);
            $responses[] = $response;
        }

        // Consolidar todas las respuestas
        $consolidatedResponse = $this->consolidateResponses($responses);

        return response()->json($consolidatedResponse);
    }

    private function translateToProvider($hubRequest, $provider)
    {
        switch ($provider) {
            case 'HotelLegs':
                return $this->translateToHotelLegs($hubRequest);
            // Agrega casos para otros proveedores...
        }
    }

    private function translateToHotelLegs($hubRequest)
    {
        $hotelLegsRequest = [
            'hotel' => $hubRequest['hotelId'],
            'checkInDate' => $hubRequest['checkIn'],
            'numberOfNights' => $this->calculateNumberOfNights($hubRequest['checkIn'], $hubRequest['checkOut']),
            'guests' => $hubRequest['numberOfGuests'],
            'rooms' => $hubRequest['numberOfRooms'],
            'currency' => $hubRequest['currency']
        ];

        // Simulación de llamada a HotelLegs API
        sleep(1); // Simula una pequeña demora

        $hotelLegsResponse = [
            'results' => $this->generateHotelLegsRates($hotelLegsRequest)
        ];

        return $hotelLegsResponse;
    }

    private function calculateNumberOfNights($checkIn, $checkOut)
    {
        $startDate = new \DateTime($checkIn);
        $endDate = new \DateTime($checkOut);
        return $endDate->diff($startDate)->days + 1;
    }

    private function generateHotelLegsRates($request)
    {
        // Genera tarifas aleatorias basadas en los parámetros de entrada
        $rates = [
            [
                'room' => rand(1, 2),
                'meal' => rand(1, 2),
                'canCancel' => rand(0, 1),
                'price' => rand(100, 200) / 100
            ],
            // Más tarifas...
        ];

        return $rates;
    }

    private function consolidateResponses($responses)
    {
        $consolidatedResponse = [
            'rooms' => []
        ];

        foreach ($responses as $response) {
            foreach ($response['results'] as $rate) {
                $found = false;
                foreach ($consolidatedResponse['rooms'] as &$room) {
                    if ($room['roomId'] === $rate['room']) {
                        $found = true;
                        $room['rates'][] = [
                            'mealPlanId' => $rate['meal'],
                            'isCancellable' => $rate['canCancel'],
                            'price' => $rate['price']
                        ];
                        break;
                    }
                }
                if (!$found) {
                    $consolidatedResponse['rooms'][] = [
                        'roomId' => $rate['room'],
                        'rates' => [$rate]
                    ];
                }
            }
        }

        return $consolidatedResponse;
    }
}
