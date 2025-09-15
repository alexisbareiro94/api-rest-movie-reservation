<?php

namespace App\Console\Commands;

use App\Jobs\DeleteQrImagesJob;
use Illuminate\Console\Command;

class DeleteQrFolder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:delete-folder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eliminar la carpeta qrcode con sus imágenes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DeleteQrImagesJob::dispatch();  
        $this->info('Job DeleteQrImagesJob despachado');          
    }
}
