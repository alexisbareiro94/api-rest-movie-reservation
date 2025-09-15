<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Showtime;
use App\Models\Room;

class ReservationService
{
    public function seats_reservation_process(Showtime $showtime, array $selectedSeats): true|string
    {
        //parámetro => $selectedSeats = array: asientos seleccionado/s Ej: [D6, A5, A7]
        $room = Room::find($showtime->room_id);

        if($showtime->seats_available == 0) {
            return "Ya no quedan asientos";
        }

        $values = [];
        foreach ($selectedSeats as $item) {
            $values[] = explode(' ', $item);
        }

        $ocupadas = [];
        if($showtime->occupied_seats){
            $ocupadas = array_intersect($selectedSeats, $showtime->occupied_seats);
        }
        if(!empty($ocupadas)){
            return "Uno o mas asientos ya están seleccionados." . ' asiento/s: '. implode(' ', $ocupadas);
        }

        $seatsSelected = 0; //$seatsSelected = int: cantidad de asientos seleccionados
        $value = $showtime->seats;

        foreach ($values as $key => $item) {
            $index = (string)$values[$key][0]; // [ 0 => 'A' , 1 => '1']
            $ocItem[] = (string)$values[$key][1];
            $arrayCount = $showtime->occupied_seats ? count($showtime->occupied_seats) : 0;// para verificar que no se sobrepase la cantidad de asientos__
            if($room->seats[$index] + $arrayCount < (int)$values[$key][1]){ //__Ej: D max = 10, y en la $req llega 11, return err
                return 'El asiento no existe';
            }
            $valueKey[] = $index;
            if ($room->seats[$index] == 0) {
                return "Ya no hay asientos disponibles para esta fila";
            }
            $restar = $value[$index] - 1;
            $value[$index] = $restar;
            $seatsSelected++;
        }
        $occupied_seats = [];
        foreach ($selectedSeats as $item) {
            $occupied_seats[] = $item;
        }

        $showtime->update([
            'seats' => $value,
            'seats_available' => $showtime->seats_available - $seatsSelected,
            'occupied_seats' => $showtime->occupied_seats ?  array_merge($showtime->occupied_seats, $occupied_seats) : $occupied_seats,
        ]);
        return true;
    }

    public function generate_code($largo = 20) :string
    {
        $string = 'a1b2c3d4e5f6g7h8i9j0klmnopqrstwyz!@#$%^&-';
        $array = str_split($string);
        $code = '';
        for ($i = 0; $i < $largo; $i++) {
            $index = random_int(0, count($array) - 1);
            $code .= $array[$index];
        }
        return $code;
    }
}
