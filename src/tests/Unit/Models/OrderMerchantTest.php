<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\OrderMerchant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class OrderMerchantTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $order;

    public function setUp() :void
    {
        parent::setUp();

        $this->order = Order::factory()->create();
    }

    /** @test */
    public function orders_merchant_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('orders_merchant', [
                'orm_id', 'ord_id', 'orm_notification_id',
                'orm_notification_topic', 'orm_order_status', 'orm_paid_amount',
            ]),
            1
        );
    }

    /** @test */
    public function a_order_merchant_belongs_to_a_order()
    {
        $orderMerchant = OrderMerchant::factory()
            ->for($this->order)
            ->create();

        $this->assertInstanceOf(Order::class, $orderMerchant->order);
    }
}
