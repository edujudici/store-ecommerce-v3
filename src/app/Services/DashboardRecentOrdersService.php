<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

class DashboardRecentOrdersService extends BaseService
{
    private $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function index(): Collection
    {
        return $this->order
            ->with('histories')
            ->limit(5)
            ->orderBy('ord_id', 'desc')
            ->get();
    }
}
