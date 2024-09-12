<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomRate extends Model
{
    protected $table = 'room_rates';

    protected $fillable = [
        'hotel_id',
        'room_id',
        'meal_plan_id',
        'is_cancellable',
        'price',
        'currency',
        'check_in_date',
        'check_out_date',
    ];

    protected $casts = [
        'price' => 'float',
        'currency' => 'string', // Agregamos currency aquí
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function mealPlan()
    {
        return $this->belongsTo(MealPlan::class);
    }
}