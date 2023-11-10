<?php

namespace App\Console\Commands;

use App\Services\Seller\AfterSalesMessageService;
use Illuminate\Console\Command;

class AfterSalesMessageCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aftersalesmessage:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send after sales message';

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
    public function handle(AfterSalesMessageService $afterSalesMessageService)
    {
        debug('Execute command aftersalesmessage:send');
        $afterSalesMessageService->send();
    }
}
