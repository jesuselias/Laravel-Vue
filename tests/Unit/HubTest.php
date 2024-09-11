<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\Hub;
use App\Connectors\HotelLegsConnector;
use GuzzleHttp\Client;
use Mockery;

class HubTest extends TestCase
{
    protected $mockClient;
    protected $mockConnector;
    protected $hub;

    protected function setUp(): void
    {
        parent::setUp();
    
        $this->mockClient = Mockery::mock(Client::class);
        $this->mockConnector = Mockery::mock(HotelLegsConnector::class);
    
        $this->hub = new Hub();
        $this->hub->_connectors['hotellegs'] = $this->mockConnector;
    }
    

    public function testSearch()
    {
        $searchData = [
            'hotelId' => 1,
            'checkIn' => '2023-01-01',
            'checkOut' => '2023-01-05',
            'numberOfGuests' => 2,
            'numberOfRooms' => 1,
            'currency' => 'EUR',
        ];

        $expectedResponse = [
            'rooms' => [
                1 => ['rates' => [
                    ['mealPlanId' => 1, 'isCancellable' => false, 'price' => 100.00],
                    ['mealPlanId' => 2, 'isCancellable' => true, 'price' => 120.00],
                ]],
            ],
        ];

        $this->mockConnector->shouldReceive('search')
            ->with($searchData)
            ->andReturn($expectedResponse);

        $result = $this->hub->search($searchData);

        $this->assertEquals($expectedResponse, $result);
    }
}
