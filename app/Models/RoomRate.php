<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomRate extends Model
{
    protected $fillable = ['roomId', 'mealPlanId', 'isCancellable', 'price'];
}
