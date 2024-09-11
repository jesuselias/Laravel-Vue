<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
    ];

    public function roomRates()
    {
        return $this->hasMany(RoomRate::class);
    }
}