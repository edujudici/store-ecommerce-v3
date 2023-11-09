<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Services\BaseService;

class OrderHistoryService extends BaseService
{
    public function store(Order $order, $request): void
    {
        $order->histories()->create($request);
    }
}
