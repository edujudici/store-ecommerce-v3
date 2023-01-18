<?php

namespace App\Jobs;

use App\Services\LoadQuestionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LoadQuestion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $offset;
    private $loadDate;
    private $mlAccountId;
    private $historyId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        $offset,
        $loadDate,
        $mlAccountId,
        $historyId
    ) {
        $this->offset = $offset;
        $this->loadDate = $loadDate;
        $this->mlAccountId = $mlAccountId;
        $this->historyId = $historyId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(LoadQuestionService $loadQuestionService)
    {
        $loadQuestionService->loadQuestions(
            $this->offset,
            $this->loadDate,
            $this->mlAccountId,
            $this->historyId
        );
    }
}
