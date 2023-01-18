<?php

namespace App\Services;

use App\Models\Order;

class OrderItemsService extends BaseService
{
    public function store(Order $order, $cart): void
    {
        $products = array_map(static function ($item) use ($order) {
            return [
                'ord_id' => $order->ord_id,
                'ori_pro_id' => $item['id'],
                'ori_pro_sku' => $item['sku'],
                'ori_amount' => $item['amount'],
                'ori_price' => $item['price'],
                'ori_title' => $item['title'],
            ];
        }, $cart['products']);
        $order->items()->createMany($products);
    }
}
