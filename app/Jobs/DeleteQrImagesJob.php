<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class DeleteQrImagesJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $rcodes = DB::table('showtimes')
            ->join('reservations', 'showtimes.id', '=', 'reservations.showtime_id')
            ->where('showtimes.date', '<', now())
            ->select('reservations.code')
            ->get();

        foreach($rcodes as $code){
            if(file_exists(public_path("qrcode/$code.png"))){
                unlink(public_path("qrcode/$code.png"));
            }
        }
    }
}
