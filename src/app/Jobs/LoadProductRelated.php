<?php

namespace App\Jobs;

use App\Services\Seller\LoadProductRelatedService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LoadProductRelated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $sku;
    private $productsRelated;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sku, $productsRelated)
    {
        $this->sku = $sku;
        $this->productsRelated = $productsRelated;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(LoadProductRelatedService $loadProductRelatedService)
    {
        $loadProductRelatedService->loadProductsRelated($this->sku, $this->productsRelated);
    }
}
