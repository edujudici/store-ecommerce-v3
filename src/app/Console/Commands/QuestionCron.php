<?php

namespace App\Console\Commands;

use App\Services\Seller\LoadQuestionService;
use Illuminate\Console\Command;

class QuestionCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'questions:load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync questions by api mercadolibre';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(LoadQuestionService $service)
    {
        debug('Execute command questions:load');
        $service->dispatchQuestions();
    }
}
