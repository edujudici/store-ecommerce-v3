<?php

namespace App\Services\Painel;

use App\Models\OrderItem;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class DashboardTopProductsService extends BaseService
{
    private $orderItem;

    public function __construct(OrderItem $orderItem)
    {
        $this->orderItem = $orderItem;
    }

    public function index(): Collection
    {
        return $this->orderItem
            ->selectRaw('
                ori_pro_sku,
                sum(ori_price) as price,
                sum(ori_amount) as amount
            ')
            ->with('product')
            ->groupBy('ori_pro_sku')
            ->orderByRaw('sum(ori_amount) DESC')
            ->limit(5)
            ->get();
    }
}
