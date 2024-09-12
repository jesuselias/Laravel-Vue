<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $table = 'room_types';

    protected $fillable = [
        'hotel_id',
        'name',
        'number',
        'max_guests',
        'max_rooms',
    ];
    
    protected $casts = [
        'max_guests' => 'integer',
        'max_rooms' => 'integer',
    ];
}