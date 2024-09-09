<?php

namespace App\Services;

use App\Connectors\HotelLegsConnector;
use Illuminate\Support\Collection;

class HubService
{
    private $connectors;

    public function __construct(HotelLegsConnector $hotelLegsConnector)
    {
        $this->connectors = [$hotelLegsConnector];
    }

    public function search(array $searchCriteria): Collection
    {
        \Log::info('Starting search with criteria:', [$searchCriteria]);

        $results = collect();

        foreach ($this->connectors as $connector) {
            \Log::info('Calling connector:', ['connector' => get_class($connector)]);
            
            try {
                $connectorResults = $connector->search($searchCriteria);
                \Log::info('Received results from connector:', [$connectorResults]);

                $results = $results->merge(collect($connectorResults));
            } catch (\Exception $e) {
                \Log::error('Error calling connector', [$e]);
            }
        }

        \Log::info('Consolidating results');
        $consolidatedResults = $this->consolidateResults($results);

        \Log::info('Final consolidated results:', [$consolidatedResults]);

        return $consolidatedResults;
    }

    private function consolidateResults(Collection $results): Collection
    {
        // Implementa la lógica para consolidar los resultados de múltiples proveedores
        // Por ejemplo, puedes agrupar por hotel, calcular promedios, etc.
        return $results;
    }
}