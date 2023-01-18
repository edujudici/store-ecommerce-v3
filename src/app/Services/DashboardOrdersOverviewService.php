<?php

namespace App\Services;

use App\Models\Order;

class DashboardOrdersOverviewService extends BaseService
{
    private $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function index(): array
    {
        return [
            'cancelled' => $this->totalCancelled(),
            'finished' => $this->totalFinished(),
            'incomplete' => $this->totalIncomplete(),
            'pending' => $this->totalPending(),
        ];
    }

    private function totalCancelled(): int
    {
        return $this->order->whereIn('orders.ord_id', function ($query) {
            $query->select('orh.ord_id')
                ->from('orders_history AS orh')
                ->where('orh.orh_collection_status', 'cancel');
        })
            // ->whereYear('created_at', '=', date('Y'))
            ->count();
    }

    private function totalFinished(): int
    {
        return $this->order->whereIn('orders.ord_id', function ($query) {
            $query->select('orh.ord_id')
                ->from('orders_history AS orh')
                ->where('orh.orh_collection_status', 'complete');
        })
            // ->whereYear('created_at', '=', date('Y'))
            ->count();
    }

    private function totalIncomplete(): int
    {
        return $this->order->whereIn('orders.ord_id', function ($query) {
            $query->select('orh.ord_id')
                ->from('orders_history AS orh')
                ->whereIn(
                    'orh.orh_collection_status',
                    ['paid', 'production', 'transport']
                );
        })
            // ->whereYear('created_at', '=', date('Y'))
            ->count();
    }

    private function totalPending(): int
    {
        return $this->order->whereIn('orders.ord_id', function ($query) {
            $query->select('orh.ord_id')
                ->from('orders_history AS orh')
                ->whereIn(
                    'orh.orh_collection_status',
                    ['new', 'payment_in_process']
                );
        })
            // ->whereYear('created_at', '=', date('Y'))
            ->count();
    }
}
