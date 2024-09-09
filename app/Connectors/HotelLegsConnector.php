<?php

namespace App\Connectors;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class HotelLegsConnector
{
    public function search(array $searchCriteria): array
    {
        \Log::info('Starting search with criteria:', [$searchCriteria]);

        // Traduce la solicitud del HUB al formato de HotelLegs
        $hotelLegsRequest = $this->translateToHotelLegsFormat($searchCriteria);
        \Log::info('Translated request:', [$hotelLegsRequest]);

        // Realiza la llamada a la API de HotelLegs
        // Por ahora, simularemos una respuesta
        $response = $this->simulateHotelLegsResponse();
        \Log::info('Simulated response:', [$response]);

        // Traduce la respuesta de HotelLegs al formato del HUB
        $translatedResponse = $this->translateFromHotelLegsFormat($response);
        \Log::info('Translated response:', [$translatedResponse]);

        return $translatedResponse;
    }

    private function simulateHotelLegsResponse(): array
    {
        return [
            "results" => [
                [
                    "room" => 1,
                    "meal" => 1,
                    "canCancel" => false,
                    "price" => 123.48
                ],
                [
                    "room" => 1,
                    "meal" => 1,
                    "canCancel" => true,
                    "price" => 150.00
                ],
                [
                    "room" => 2,
                    "meal" => 1,
                    "canCancel" => false,
                    "price" => 148.25
                ],
                [
                    "room" => 2,
                    "meal" => 2,
                    "canCancel" => false,
                    "price" => 165.38
                ]
            ]
        ];
    }

    private function translateToHotelLegsFormat(array $hubSearchCriteria): array
    {
        return [
            'hotel' => $hubSearchCriteria['hotelId'],
            'checkInDate' => $hubSearchCriteria['checkIn'],
            'numberOfNights' => date_diff(new DateTime($hubSearchCriteria['checkOut']), new DateTime($hubSearchCriteria['checkIn']))->days,
            'guests' => $hubSearchCriteria['numberOfGuests'],
            'rooms' => $hubSearchCriteria['numberOfRooms'],
            'currency' => $hubSearchCriteria['currency'],
        ];
    }

    private function translateFromHotelLegsFormat(array $hotelLegsResponse): array
    {
        $rooms = [];
        foreach ($hotelLegsResponse['results'] as $result) {
            $roomId = $result['room'];
            if (!isset($rooms[$roomId])) {
                $rooms[$roomId] = ['roomId' => $roomId, 'rates' => []];
            }
            $rooms[$roomId]['rates'][] = [
                'mealPlanId' => $result['meal'],
                'isCancellable' => $result['canCancel'],
                'price' => $result['price'],
            ];
        }
        return ['rooms' => array_values($rooms)];
    }
}