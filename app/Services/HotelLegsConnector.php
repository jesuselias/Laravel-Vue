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
use Illuminate\Http\Request;

class HotelLegsConnector
{
    public function search($input)
    {
        if ($input instanceof Request) {
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
    
        return $result;
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

        $filteredRoomTypes = $roomTypes->filter(function ($roomType) use ($guests, $rooms) {
            return $roomType->max_guests >= $guests && $roomType->max_rooms >= $rooms;
        });
        

        return $filteredRoomTypes->map(function ($roomType) use ($checkInDate, $currency, $numberOfNights) {
            $roomRates = RoomRate::where('room_id', $roomType->id)
                ->where('check_in_date', '>=', $checkInDate)
                ->where('number_of_nights', '>=', $numberOfNights)
                ->where('currency', $currency)
                ->orderBy('price')
                ->get();
    
            if ($roomRates->isNotEmpty()) {
                $lastRate = $roomRates->last();
                $mealPlanId = MealPlan::find($lastRate->meal_plan_id)->id ?? null;
                
                return [
                    'room' => $roomType->id,
                    'meal' => $mealPlanId,
                    'canCancel' => $lastRate->is_cancellable,
                    'price' => floatval($lastRate->price),
                ];
            } else {
                return null; // Devuelve null si no se encuentra ninguna tarifa válida
            }
        })->filter()->values()->all();
    } 
}