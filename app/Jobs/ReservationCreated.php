<?php

namespace App\Jobs;

use App\Mail\ReservationCreated as reservation_crated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ReservationCreated implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $reservation;
    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {                
        Mail::to($this->reservation->user->email)->queue(new reservation_crated($this->reservation));        
    }
}
