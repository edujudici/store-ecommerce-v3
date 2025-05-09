<?php

namespace App\Jobs;

use App\Services\Seller\LoadCategoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LoadCategory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $mlAccountId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mlAccountId)
    {
        $this->mlAccountId = $mlAccountId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(LoadCategoryService $loadCategoryService)
    {
        $loadCategoryService->organizeCategories($this->mlAccountId);
    }
}
