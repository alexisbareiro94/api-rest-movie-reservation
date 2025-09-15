<?php

use App\Jobs\DeleteQrImagesJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\DeleteQrFolder;
use App\Models\Showtime;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('qr:delete-folder')->daily();
//Schedule::job(new DeleteQrImagesJob)->everyTenSeconds();

