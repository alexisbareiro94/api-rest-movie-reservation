<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';
    protected $fillable = [
        'number',
        'status',
        'capacity',
        'seats',
        //'seats_available',
        //'occupied_seats',
    ];

    public function showtimes(): \Illuminate\Database\Eloquent\Relations\HasMany|Room
    {
        return $this->hasMany(Showtime::class, 'room_id');
    }

    public function casts() :array{
        return [
            'seats' => 'array',
            //11'occupied_seats' => 'array',
        ];
    }
}
