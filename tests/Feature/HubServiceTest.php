<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\HubService;
use Carbon\Carbon;

class HubServiceTest extends TestCase
{
    public function test_it_exists_and_has_the_right_methods()
    {
        $hubService = new HubService();
        
        $methods = get_class_methods(get_class($hubService));
        $expectedMethods = ['search', 'getHotelLegsData'];
        
        sort($methods);
        sort($expectedMethods);
        
        $this->assertEquals(array_intersect($methods, $expectedMethods), $methods);
    }

    public function test_search_method_returns_expected_structure()
    {
        $hubService = new HubService();

        $input = [
            'hotelId' => '123',
            'checkIn' => '2024-03-15',
            'checkOut' => '2024-03-20',
            'numberOfGuests' => '3',
            'numberOfRooms' => '2',
            'currency' => 'usd'
        ];

        $result = $hubService->search($input);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('rooms', $result);
        $this->assertIsArray($result['rooms']);
        
    }
}