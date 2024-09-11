<?php

namespace App\Connectors;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class HotelLegsConnector
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Realiza una búsqueda de habitaciones en el hub.
     *
     * @param array $searchData Datos de búsqueda
     * @return array Resultados de la búsqueda transformados
     */
    public function search(array $searchData): array
    {
        try {
            $response = $this->client->post('http://localhost:8000/api/hub/search', [
                'json' => $this->transformSearchRequest($searchData),
            ]);

            return $this->handleResponse($response);
        } catch (\Exception $e) {
            Log::error('Error en HotelLegsConnector.search: ' . $e->getMessage());
            throw $e;
        }
    }

    private function transformSearchRequest(array $searchData): array
    {
        $validatedData = $this->validateSearchData($searchData);

        return [
            'hotel' => $validatedData['hotel_id'],
            'checkInDate' => $validatedData['created_at'],
            'numberOfNights' => $this->calculateNumberOfNights($validatedData['created_at'], $validatedData['updated_at']),
            'guests' => $validatedData['numberOfGuests'],
            'rooms' => $validatedData['numberOfRooms'],
            'currency' => $validatedData['currency'],
        ];
    }

    private function validateSearchData(array $data): array
    {
        return [
            'hotelId' => $this->ensureInteger($data['hotel_id']),
            'checkIn' => $this->ensureDateFormat($data['created_at']),
            'checkOut' => $this->ensureDateFormat($data['updated_at']),
            'numberOfGuests' => $this->ensureInteger($data['numberOfGuests']),
            'numberOfRooms' => $this->ensureInteger($data['numberOfRooms']),
            'currency' => $this->ensureString($data['currency']),
        ];
    }

    private function ensureInteger($value): int
    {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException("El valor debe ser numérico");
        }
        return (int)$value;
    }

    private function ensureDateFormat($date): string
    {
        if (!strtotime($date)) {
            throw new \InvalidArgumentException("La fecha debe estar en formato válido (YYYY-MM-DD)");
        }
        return $date;
    }

    private function ensureString($value): string
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException("El valor debe ser una cadena");
        }
        return $value;
    }

    private function calculateNumberOfNights(string $checkIn, string $checkOut): int
    {
        $startDate = new \DateTime($checkIn);
        $endDate = new \DateTime($checkOut);
        return $startDate->diff($endDate)->days + 1;
    }

    private function handleResponse($response): array
    {
        Log::info("Respuesta completa:", ['response' => json_encode($response)]);
        
        $responseData = json_decode($response->getBody(), true);
    
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new \RuntimeException("Respuesta HTTP inválida: " . $response->getReasonPhrase());
        }
    
        Log::info("Datos procesados:", ['data' => $responseData]);
    
        return $this->transformResponse($responseData);
    }
    

    private function transformResponse(array $responseData): array
    {
        $rooms = Collection::make($responseData['results'] ?? [])
            ->groupBy('room')
            ->map(function ($items) {
                return [
                    'roomId' => $items[0]['room'],
                    'rates' => $items->pluck('price', 'meal')
                        ->mapWithKeys(function ($price, $meal) {
                            return [$meal => [
                                'mealPlanId' => $meal,
                                'isCancellable' => in_array($meal, [1, 2]),
                                'price' => $price,
                            ]];
                        })
                        ->values()
                ];
            });
    
        return [
            'rooms' => $rooms->all(),
        ];
    }
    
}
