<?php

namespace App\Http\Middleware;

use App\Models\Reservation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReservationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $reservation = Reservation::find($request->id);
        if(!$reservation){
            return response()->json([
                'error' => 'Reservation not found',
            ], 404);
        }
        if($request->user()->id != $reservation->user_id){
            return response()->json([
                'message' => 'sin autorización',
            ]);
        }
        return $next($request);
    }
}
