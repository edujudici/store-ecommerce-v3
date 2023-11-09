<?php

namespace App\Jobs;

use App\Services\Seller\LoadMultipleService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LoadProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $loadDate;
    private $mlAccountId;
    private $skus;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        $loadDate,
        $mlAccountId,
        $skus
    ) {
        $this->loadDate = $loadDate;
        $this->mlAccountId = $mlAccountId;
        $this->skus = $skus;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(LoadMultipleService $loadService)
    {
        $loadService->loadProducts(
            $this->loadDate,
            $this->mlAccountId,
            $this->skus
        );
    }
}
