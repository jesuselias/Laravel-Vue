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


class HubService
{
  

    public function search($input)
    {
        if ($input instanceof Request) {
            $data = $input->all();
        } else {
            $data = $input;
        }
    
        // Convertir fechas a formato ISO 8601
        if (isset($data['checkIn'])) {
            $data['checkIn'] = Carbon::parse($data['checkIn'])->format('Y-m-d');
        }
        if (isset($data['checkOut'])) {
            $data['checkOut'] = Carbon::parse($data['checkOut'])->format('Y-m-d');
        }
    
        // Resto del código...
        $result = $this->getHotelLegsData(
            $data['hotelId'],
            $data['checkIn'],
            $data['checkOut'],
            $data['numberOfGuests'],
            $data['numberOfRooms'],
            $data['currency']
        );
    
        return [
            'rooms' => $result
        ];
    }

    private function validateInput(array $data): array
    {
        return [
            'hotelId' => intval($data['hotelId']),
            'checkIn' => Carbon::parse($data['checkIn'])->format('Y-m-d'),
            'checkOut' => Carbon::parse($data['checkOut'])->format('Y-m-d'),
            'numberOfGuests' => intval($data['numberOfGuests']),
            'numberOfRooms' => intval($data['numberOfRooms']),
            'currency' => strtoupper($data['currency'])
        ];
    }
    
public function getHotelLegsData($hotelId, $checkIn, $checkOut, $guests, $rooms, $currency): array
{
    // Validar fechas
    $checkIn = Carbon::parse($checkIn)->format('Y-m-d');
    $checkOut = Carbon::parse($checkOut)->format('Y-m-d');

    if (!$checkIn || !$checkOut) {
        throw new \InvalidArgumentException("Las fechas de check-in y check-out deben ser válidas.");
    }

    // Obtener los tipos de habitación disponibles para este hotel
    $roomTypes = RoomType::where('hotel_id', $hotelId)->get();

    // Obtener todos los planes de comida disponibles
    $mealPlans = MealPlan::pluck('id', 'name');

    // Filtrar los tipos de habitación según la selección
    $filteredRoomTypes = $roomTypes->filter(function ($roomType) use ($guests, $rooms) {
        return true; // Simplemente devuelve todos los resultados por ahora
    });

    return $filteredRoomTypes->map(function ($roomType) use ($mealPlans, $checkIn, $checkOut) {
        $roomRates = RoomRate::where('room_id', $roomType->id)
            ->whereBetween('check_in_date', [$checkIn, $checkOut])
            ->whereBetween('check_out_date', [$checkIn, $checkOut])
            ->get();

        $rates = [];
        foreach ($roomRates as $rate) {
            $mealPlanId = MealPlan::where('id', $rate->meal_plan_id)->first()->id ?? null;
            $mealPlanName = $mealPlanId !== null ? MealPlan::find($mealPlanId)->name : 'No plan selected';

            $rates[] = [
                'mealPlanId' => $mealPlanId,
                'isCancellable' => $rate->is_cancellable,
                'price' => floatval($rate->price),
            ];
        }

        return [
            'roomId' => $roomType->id,
            'rates' => $rates
        ];
    })->all();
    }

        
    private function translateHotelLegsResponse(array $response): array
    {
        $translatedResponse = [];
        foreach ($response as $item) {
            $roomTypeName = RoomType::find($item['roomId'])->name ?? 'Unknown';
            $mealPlan = MealPlan::find($item['mealPlanId']);
            $mealPlanId = $mealPlan ? $mealPlan->id : null;

            $translatedItem = [
                'roomId' => $roomTypeName,
                'rates' => []
            ];

            foreach ($item['rates'] as $rate) {
                $translatedItem['rates'][] = [
                    'mealPlanId' => $mealPlanId,
                    'isCancellable' => $rate['isCancellable'],
                    'price' => $rate['price']
                ];
            }

            $translatedResponse[] = $translatedItem;
        }

        return ['rooms' => $translatedResponse];
    }
}