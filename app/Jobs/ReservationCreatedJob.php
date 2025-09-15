<?php

namespace App\Jobs;

use App\Jobs\DeleteQrImagesJob;
use App\Mail\ReservationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class ReservationCreatedJob implements ShouldQueue
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
        Mail::to($this->reservation->user->email)->send(new ReservationCreated($this->reservation));        
        //DeleteQrImagesJob::dispatch();
    }
}
