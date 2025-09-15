<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Jobs\ReservationCreatedJob;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Showtime;
use App\Services\ReservationService;
use Illuminate\Support\Facades\Gate;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class ReservationController extends Controller
{

    public function __construct(protected ReservationService $reservationService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->role == 'admin') {
            $query = Reservation::query();
            $total = Reservation::select('amount')->get();
            $showtime = Showtime::query();
            $totalAvShow = $query->where(function () use ($showtime) {
                $showtime->where('date', '<', now());
            })->sum('amount');

            return response()->json([
                'count' => count($query->get()),
                'total_revenue' => $total->sum('amount'),
                'revenue_available_showtimes' => $totalAvShow,
                'reservations' => $query->paginate(15),
            ]);
        } else {
            $reservations = Reservation::all();
            $count = count($reservations);
            $authorizedReservations = $reservations->filter(function ($reservation) {
                return Gate::allows('view', $reservation);
            });

            return response()->json([
                'count' => $count,
                'reservations' => $authorizedReservations,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'showtime_id' => 'required|exists:showtimes,id',
            'seats' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'error/s:' . $validator->messages(),
            ]);
        }
        try {
            $showtime = Showtime::find($request->showtime_id);
            $selectedSeats = explode(',', $request->seats);
            $res = $this->reservationService->seats_reservation_process($showtime, $selectedSeats);
            if (gettype($res) != "boolean") {
                return response()->json([
                    'error' => $res,
                ], 400);
            }
            try {
                $reservation = Reservation::create([
                    'user_id' => $request->user()->id,
                    'showtime_id' => $request->showtime_id,
                    'seats' => explode(',', $request->seats),
                    'code' => $this->reservationService->generate_code(),
                    'amount' => $showtime->price * count($selectedSeats),
                ]);
                //generación del qr
                $qrPath = "qrcode/$reservation->code.png"; //el public_path es personalizado para no usar Storage xd,
                Storage::disk('public_path')->put($qrPath, QrCode::format('png')->size('300')->generate($reservation->code));
                ReservationCreatedJob::dispatch($reservation); //ejecutar las jobs

                return response()->json([
                    'message' => 'reservation creada',
                    'reservation' => $reservation,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage(),
                ], 400);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 'Error en disminuir asientos');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reservation = Reservation::find($id);
        if(!$reservation){
            return response()->json([
                'error' => 'Reservation not found',
            ], 404);
        }
        try{
            $reservation->delete();
        }catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
        //si la reservacion se borra:
        $showtime = Showtime::findOrFail($reservation->showtime_id);
        $showtime->update([
            'occupied_seats' => array_diff($showtime->occupied_seats, $reservation->seats),
            'seats_available' => $showtime->seats_available + count($reservation->seats),
        ]);
        return response()->json([
            'message' => 'reservation deleted',
        ], 200);
    }
}
