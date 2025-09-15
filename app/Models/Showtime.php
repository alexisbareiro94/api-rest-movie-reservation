<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    protected $table = 'showtimes';
    protected $fillable = [
        'movie_id',
        'room_id',
        'start_time',
        'end_time',
        'date',
        'status',
        'seats_available',
        'occupied_seats',
        'seats',
        'price'
    ];

    public function movie(): \Illuminate\Database\Eloquent\Relations\BelongsTo{
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    public function room(): \Illuminate\Database\Eloquent\Relations\BelongsTo{
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function reservation(): \Illuminate\Database\Eloquent\Relations\HasMany|Showtime{
        return $this->hasMany(Reservation::class, 'showtime_id');
    }

    public function  casts() :array{
        return [
            'occupied_seats' => 'array',
            'seats' => 'array',
        ];
    }
}
