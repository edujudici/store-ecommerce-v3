<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class OrderItemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp() :void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->order = Order::factory()
            ->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function orders_item_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('orders_item', [
                'ori_id', 'ord_id', 'ori_pro_id', 'ori_pro_sku', 'ori_amount',
                'ori_price', 'ori_title',
            ]),
            1
        );
    }

    /** @test */
    public function a_order_item_belongs_to_a_order()
    {
        $orderItem = OrderItem::factory()
            ->create(['ord_id' => $this->order->ord_id]);

        $this->assertInstanceOf(Order::class, $orderItem->order);
    }

    /** @test */
    public function a_order_item_has_a_product()
    {
        $product = Product::factory()->create();
        $orderItem = OrderItem::factory()
            ->create([
                'ord_id' => $this->order->ord_id,
                'ori_pro_sku' => $product->pro_sku
            ]);

        $this->assertInstanceOf(Product::class, $orderItem->product);
        $this->assertEquals(1, $orderItem->product->count());
    }
}
