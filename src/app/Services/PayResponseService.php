<?php

namespace App\Services;

use App\Jobs\PayNotification;

class PayResponseService extends BaseService
{
    private $orderService;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct(
        OrderService $orderService
    ) {
        $this->orderService = $orderService;
    }

    /**
     * Get data to display on complete response payment
     *
     * @param [array] $request
     *
     * @return array
     */
    public function confirmation($request): array
    {
        $order = $this->orderService->getOrderByPreference(
            $request->input('preference_id')
        );
        return [
            'order' => $order->toArray(),
            'items' => $order->items->toArray(),
            'address' => $order->address->toArray(),
        ];
    }

    /**
     * Add notification of payment in queue
     *
     * @param [array] $request
     *
     * @return void
     */
    public function notification($request): void
    {
        $params = $request->all();
        debug(['debug notification MP' => $params]);
        PayNotification::dispatch($params)->onQueue('payment');
    }
}
