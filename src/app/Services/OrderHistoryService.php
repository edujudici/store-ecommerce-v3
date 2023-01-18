<?php

namespace App\Services;

use App\Models\Order;

class OrderHistoryService extends BaseService
{
    public function store(Order $order, $request): void
    {
        $order->histories()->create($request);
    }
}
