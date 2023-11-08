<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::factory()
            ->count(5)
            ->has(
                OrderItem::factory()
                    ->count(3)
                    ->state(new Sequence(
                        fn ($sequence) => ['ori_pro_sku' => Product::pluck('pro_sku')->random()],
                    )),
                'items'
            )
            ->state(new Sequence(
                fn ($sequence) => ['user_id' => User::where('name', 'shopper')->first()],
            ))
            ->hasAddress(1)
            ->hasComments(3)
            ->has(
                OrderHistory::factory()
                    ->count(6)
                    ->state(new Sequence(
                        ['orh_collection_status' => Order::STATUS_NEW, 'created_at' => date('Y-m-d ') . '07:59:59'],
                        ['orh_collection_status' => Order::STATUS_PAYMENT_IN_PROCESS, 'created_at' => date('Y-m-d ') . '08:59:59'],
                        ['orh_collection_status' => Order::STATUS_PAID, 'created_at' => date('Y-m-d ') . '09:59:59'],
                        ['orh_collection_status' => Order::STATUS_PRODUCTION, 'created_at' => date('Y-m-d ') . '10:59:59'],
                        ['orh_collection_status' => Order::STATUS_TRANSPORT, 'created_at' => date('Y-m-d ') . '11:59:59'],
                        ['orh_collection_status' => Order::STATUS_COMPLETE, 'created_at' => date('Y-m-d ') . '12:59:59'],
                    )),
                'histories'
            )
            ->create();
    }
}
