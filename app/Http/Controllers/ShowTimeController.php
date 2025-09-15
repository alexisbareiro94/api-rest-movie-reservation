<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Showtime;
use App\Services\ShowTimeService;

class ShowTimeController extends Controller
{
    public function __construct(protected ShowTimeService $show_time) {}

    public function index(Request $request)
    {
        

    }

    public function store(Request $request)
    {
        if (!$request->user()->tokenCan('create')) {
            return response()->json([
                'message' => 'no tienes autorización'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required',
            'end_time' => 'required',
            'date' => 'required|date',
            'status' => 'required',
            'price' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages(),
            ]);
        }

        $movie = Movie::find($request->movie_id);
        $start = Carbon::parse($request->start_time);
        $end_time = $start->addMinutes($movie->duration)->format('H:i');

        try {
            $room = Room::find($request->room_id);
            $showtime = ShowTime::create([
                'movie_id' => $request->movie_id,
                'room_id' => $request->room_id,
                'start_time' => $request->start_time,
                'end_time' => $end_time ?? $request->end_time,
                'date' => $request->date,
                'status' => $request->status,
                'seats' => $room->seats ?? 0,
                'seats_available' => $room->capacity,
                'occupied_seats' => [],
                'price' => $request->price,
            ]);

            return response()->json([
                'message' => 'showtime create successfully',
                $showtime
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function show(string $id)
    {
        return response()->json([
            ShowTime::where('id', $id)->with('Movie')->with('Room')->first(),
        ]);
    }

    public function update(Request $request, string $id) {}

    public function destroy(string $id) {}
}
