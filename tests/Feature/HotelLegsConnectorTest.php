<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\HotelLegsConnector;
use Carbon\Carbon;

class HotelLegsConnectorTest extends TestCase
{
    public function test_search_method_returns_expected_structure()
    {
        $hotelLegsConnector = new HotelLegsConnector();

        $input = [
            'hotel' => '123',
            'checkInDate' => '2024-03-15',
            'numberOfNights' => '3',
            'guests' => '2',
            'rooms' => '1',
            'currency' => 'usd'
        ];

        $result = $hotelLegsConnector->search($input);

        $this->assertIsArray($result);
        
        // Verificar si hay algún elemento en el resultado
        if (!empty($result)) {
            $firstElement = reset($result);
            
            // Verificar la estructura típica de un elemento
            $this->assertArrayHasKey('room', $firstElement);
            $this->assertArrayHasKey('meal', $firstElement);
            $this->assertArrayHasKey('canCancel', $firstElement);
            $this->assertArrayHasKey('price', $firstElement);

            $this->assertIsInt($firstElement['room']);
            $this->assertIsInt($firstElement['meal'] ?? null); // Meal puede ser null
            $this->assertIsBool($firstElement['canCancel']);
            $this->assertIsNumeric($firstElement['price']);
        }
    }

    public function test_search_method_returns_something()
    {
        $hotelLegsConnector = new HotelLegsConnector();

        $input = [
            'hotel' => '123',
            'checkInDate' => '2024-03-15',
            'numberOfNights' => '3',
            'guests' => '2',
            'rooms' => '1',
            'currency' => 'usd'
        ];

        $result = $hotelLegsConnector->search($input);

        $this->assertNotNull($result);
        $this->assertIsArray($result);
    }
}