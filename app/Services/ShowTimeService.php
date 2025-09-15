<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Showtime;

class ShowTimeService
{
    public function update(){
        $showtimes = ShowTime::select('id','movie_id','room_id','start_time','price', 'seats_available',)
            ->where('status', 'available')
            ->orderBy('status', 'asc')
            ->with([
                'Movie' => function ($query) {
                    $query->select('id','title', 'description', 'image', 'duration');
                }
            ])
            ->with([
                'Room' => function ($query) {
                    $query->select('id','capacity');
                }
            ])->get();
        $count = count($showtimes);
        return response()->json([
            'count' => $count,
            'showtimes' => $showtimes->toArray(),
        ]);
    }
}
