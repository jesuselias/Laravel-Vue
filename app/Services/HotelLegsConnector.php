<?php

namespace App\Services;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\RoomType;
use App\Models\MealPlan;
use App\Models\RoomRate;

class HotelLegsConnector
{
    public function search($input)
    {
        if ($input instanceof \Illuminate\Http\Request) {
            $data = $input->all();
        } else {
            $data = $input;
        }
    
        // Convertir fechas a formato ISO 8601
        if (isset($data['checkInDate'])) {
            $data['checkInDate'] = Carbon::parse($data['checkInDate'])->format('Y-m-d');
        }
    
        // Resto del código...
        $result = $this->getHotelLegsData(
            $data['hotel'],
            $data['checkInDate'],
            $data['numberOfNights'],
            $data['guests'],
            $data['rooms'],
            $data['currency']
        );
    
        return [
            'results' => $result
        ];
    }

    private function validateInput(array $data): array
    {
        return [
            'hotel' => intval($data['hotel']),
            'checkInDate' => Carbon::parse($data['checkInDate'])->format('Y-m-d'),
            'numberOfNights' => intval($data['numberOfNights']),
            'guests' => intval($data['guests']),
            'rooms' => intval($data['rooms']),
            'currency' => strtoupper($data['currency'])
        ];
    }

    private function getHotelLegsData($hotel, $checkInDate, $numberOfNights, $guests, $rooms, $currency): array
    {
        // Validar fechas
        if (!$checkInDate) {
            throw new \InvalidArgumentException("La fecha de check-in debe ser válida.");
        }

        // Obtener los tipos de habitación disponibles para este hotel
        $roomTypes = RoomType::where('hotel_id', $hotel)->get();

        // Obtener todos los planes de comida disponibles
        $mealPlans = MealPlan::pluck('id', 'name');

        // Filtrar los tipos de habitación según la selección
        $filteredRoomTypes = $roomTypes->filter(function ($roomType) use ($guests, $rooms) {
            return true; // Simplemente devuelve todos los resultados por ahora
        });

        return $filteredRoomTypes->map(function ($roomType) use ($mealPlans, $checkInDate, $guests, $rooms) {
            $roomRates = RoomRate::where('room_id', $roomType->id)
                ->whereBetween('check_in_date', [$checkInDate, Carbon::parse($checkInDate)->addDays($numberOfNights - 1)])
                ->get();

            $rates = [];
            foreach ($roomRates as $rate) {
                $mealPlanId = $rate->meal_plan_id ?? null;
                $mealPlanName = $mealPlanId !== null ? MealPlan::find($mealPlanId)->name : 'No plan selected';

                $rates[] = [
                    'mealPlanId' => $mealPlanId,
                    'isCancellable' => $rate->is_cancellable,
                    'price' => floatval($rate->price),
                ];
            }

            return [
                'room' => $roomType->id,
                'meal' => $mealPlanId,
                'canCancel' => $rate->is_cancellable,
                'price' => floatval($rate->price),
            ];
        })->all();
    }
}