<?php

namespace App\Jobs;

use App\Services\LoadDescriptionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LoadProductDescription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $skus;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($skus)
    {
        $this->skus = $skus;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(LoadDescriptionService $loadDescriptionService)
    {
        $loadDescriptionService->loadDescriptions($this->skus);
    }
}
