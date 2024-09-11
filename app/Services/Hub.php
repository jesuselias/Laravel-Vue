<?php

namespace App\Services;

use App\Connectors\HotelLegsConnector;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class Hub
{
    private $connectors;

    public function __construct()
    {
        $this->connectors = [
            'hotellegs' => new HotelLegsConnector(new Client()),
        ];
    }

    public function search(array $searchData)
    {
        $results = [];
        foreach ($this->connectors as $connectorName => $connector) {
            try {
                $logMessage = "Llamando al conector {$connectorName}";
                Log::info($logMessage);
                
                $connectorResults = $connector->search($searchData);
                $logMessage = "Resultado del conector {$connectorName}: " . json_encode($connectorResults);
                Log::info($logMessage);
                
                $results[] = $this->processConnectorResults($connectorResults);
            } catch (\Exception $e) {
                $logMessage = "Error en el conector {$connectorName}: " . $e->getMessage();
                Log::error($logMessage);
            }
        }
        
        $logMessage = "Resultados finales: " . json_encode($results);
        Log::info($logMessage);
        
        return $this->aggregateResults($results);
    }
    

    private function processConnectorResults(array $result)
    {
        return array_map(function ($roomData) {
            return [
                'roomId' => $roomData['id'],
                'rates' => array_map(function ($rate) {
                    return [
                        'mealPlanId' => $rate['plan_id'],
                        'isCancellable' => $rate['cancellable'] ?? false,
                        'price' => $rate['price']
                    ];
                }, $roomData['rates']),
            ];
        }, $result['results']);
    }

 private function aggregateResults(array $results)
{
    $aggregatedRooms = [];
    
    foreach ($results as $connectorResults) {
        foreach ($connectorResults['rooms'] as $roomId => $roomData) {
            if (!isset($aggregatedRooms[$roomId])) {
                $aggregatedRooms[$roomId] = ['rates' => []];
            }
            
            $aggregatedRooms[$roomId]['rates'] = array_merge(
                $aggregatedRooms[$roomId]['rates'],
                $roomData['rates']
            );
        }
    }
    
    return count($aggregatedRooms) > 0 ? ['rooms' => $aggregatedRooms] : ['rooms' => []];
}

}

