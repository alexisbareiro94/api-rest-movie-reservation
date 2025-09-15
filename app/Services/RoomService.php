<?php

namespace App\Services;
class RoomService
{
    public function process_seats(int $valor, int $capacity) :array {//devuelve el array de los asientos ['A' => 1,2,3 'B' => 1,2,3]
        $seats = [];
        for($i = 0; $i < $valor; $i++){
            $char = chr(65 + $i);
            $seats[$char] = $valor;
        }

        if($capacity > $valor * $valor){
            $toAdd = $capacity - $valor * $valor;
            foreach($seats as $key => $item){
                $seats[$key] = $item + $toAdd;
                break;
            }
        }
        return $seats;
    }
}
