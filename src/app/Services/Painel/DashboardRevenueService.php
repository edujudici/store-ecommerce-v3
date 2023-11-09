<?php

namespace App\Services\Painel;

use App\Models\Order;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class DashboardRevenueService extends BaseService
{
    private $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function indexRevenue(): Collection
    {
        return $this->order->selectRaw('
            count(ord_id) as total,
            YEAR(created_at) year,
            MONTH(created_at) month
        ')
            ->whereIn('orders.ord_id', function ($query) {
                $query->select('orh.ord_id')
                    ->from('orders_history AS orh')
                    ->whereRaw("orh.orh_collection_status = 'paid'");
            })
            // ->whereRaw('YEAR(created_at) = YEAR(NOW())')
            ->groupBy('year', 'month')
            ->get();
    }

    public function indexSalesYear(): Collection
    {
        return $this->order->selectRaw('
            count(ord_id) as total,
            sum(ord_total) as revenue,
            YEAR(created_at) year,
            MONTH(created_at) month
        ')
            ->whereIn('orders.ord_id', function ($query) {
                $query->select('orh.ord_id')
                    ->from('orders_history AS orh')
                    ->whereRaw("orh.orh_collection_status = 'paid'");
            })
            // ->whereRaw('YEAR(created_at) = YEAR(NOW())')
            ->groupBy('year', 'month')
            ->get();
    }
}
