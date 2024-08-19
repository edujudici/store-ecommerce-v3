<?php

namespace App\Listeners;

use App\Events\ProductVisited;
use App\Services\Product\ProductVisitedService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewProductVisited implements ShouldQueue
{
    private $productVisitedService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ProductVisitedService $productVisitedService)
    {
        $this->productVisitedService = $productVisitedService;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ProductVisited  $event
     * @return void
     */
    public function handle(ProductVisited $event)
    {
        $this->productVisitedService->store($event->sku());
    }
}
