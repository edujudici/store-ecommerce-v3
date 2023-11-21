<?php

namespace App\Jobs;

use App\Services\Payment\PayService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PayNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    private $payClientInterface;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $payClientInterface)
    {
        $this->data = $data;
        $this->payClientInterface = $payClientInterface;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(PayService $payService)
    {
        $payService->processNotification($this->data, $this->payClientInterface);
    }
}
