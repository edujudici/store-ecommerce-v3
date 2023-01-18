<?php

namespace App\Jobs;

use App\Services\LoadMultipleService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LoadProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $offset;
    private $loadDate;
    private $mlAccountId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        $offset,
        $loadDate,
        $mlAccountId
    ) {
        $this->offset = $offset;
        $this->loadDate = $loadDate;
        $this->mlAccountId = $mlAccountId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(LoadMultipleService $loadService)
    {
        $loadService->loadProducts(
            $this->offset,
            $this->loadDate,
            $this->mlAccountId
        );
    }
}
