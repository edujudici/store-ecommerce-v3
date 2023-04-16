<?php

namespace App\Jobs;

use App\Services\LoadPictureService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LoadProductPicture implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $sku;
    private $pictures;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sku, $pictures)
    {
        $this->sku = $sku;
        $this->pictures = $pictures;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(LoadPictureService $loadPictureService)
    {
        $loadPictureService->loadPictures($this->sku, $this->pictures);
    }
}
