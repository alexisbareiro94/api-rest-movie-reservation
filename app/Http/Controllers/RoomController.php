<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Services\RoomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public function __construct(protected RoomService $roomService) {}

    public function index(Request $request)
    {
        if (!$request->user()->tokenCan('create')) {
            return response()->json([
                'message' => 'no estas autorizado'
            ]);
        }
        $rooms = Room::all();
        $count = count($rooms);
        return response()->json([
            'count' => $count,
            'rooms' => $rooms,
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->user()->tokenCan('create')) {
            return response()->json([
                'message' => 'no estas autorizado',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'number' => 'required|numeric|unique:rooms,number',
            'status' => 'required',
            'capacity' => 'required',
//            'seats_available' => 'nullable',
//            'occupied_seats' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages(),
            ]);
        }

        $valor = (int)(sqrt($request->capacity));
        $capacity = $request->capacity;
        $seats = $this->roomService->process_seats($valor, $capacity);
        //dd($seats);
        try {
            $room = Room::create([
                'number' => $request->number,
                'status' =>  $request->status,
                'capacity' => $request->capacity,
                'seats' => $seats,
                //'seats_available' => $request->seats_available,
                //'occupied_seats' => $request->occupied_seats,
            ]);
            return response()->json([
                'message' => 'room created successfully',
                $room,
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function show(string $id)
    {
        return Room::find($id);
    }

    public function update(Request $request, string $id)
    {
        if (!$request->user()->tokenCan('edit')) {
            return response()->json([
                'message' => 'sin autorización',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'number' => 'sometimes|numeric|unique:rooms,number',
            'status' => 'sometimes',
            'capacity' => 'sometimes',
//            'seats_available' => 'nullable',
//            'occupied_seats' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages(),
            ]);
        }

        try {
            if ($request->capacity) {
                $valor = (int)(sqrt($request->capacity));
                $capacity = $request->capacity;
                $seats = $this->roomService->process_seats($valor, $capacity);
            }
            //dd($seats);
            $room = Room::findOrFail($id);
            $room->update([
                'number' => $request->number ?? $room->number,
                'status' => $request->status ?? $room->status,
                'capacity' => $request->capacity ?? $room->capacity,
                'seats' => $seats ?? $room->seats,
//                'seats_available' => $request->capacity ? $request->capacity : $room->seats_available,
//                'occupied_seats' => $request->occupied_seats ?? $room->occupied_seats,
             ]);
            return response([
                'message' => 'room actualizado'
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function destroy(string $id, Request $request) {
        if(!$request->user()->tokenCan('destroy')){
            return response()->json([
                'message' => 'si autorización',
            ]);
        }

        try{
            Room::destroy($id);
            return response()->json([
                'message' => 'recurso eliminado',
            ]);
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }
}
