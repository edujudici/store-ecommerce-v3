<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\OrderPayment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class OrderPaymentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $order;

    public function setUp() :void
    {
        parent::setUp();

        $this->order = Order::factory()->create();
    }

    /** @test */
    public function orders_payment_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('orders_payment', [
                'orp_id', 'ord_id', 'orp_payment_id', 'orp_order_id',
                'orp_payer_id', 'orp_payer_email', 'orp_payer_first_name',
                'orp_payer_last_name', 'orp_payer_phone',
                'orp_payment_method_id', 'orp_payment_type_id', 'orp_status',
                'orp_status_detail', 'orp_transaction_amount',
                'orp_received_amount', 'orp_resource_url',
                'orp_total_paid_amount', 'orp_shipping_amount',
                'orp_date_approved', 'orp_date_created',
                'orp_date_of_expiration', 'orp_live_mode',
            ]),
            1
        );
    }

    /** @test */
    public function a_order_payment_belongs_to_a_order()
    {
        $orderPayment = OrderPayment::factory()
            ->for($this->order)
            ->create();

        $this->assertInstanceOf(Order::class, $orderPayment->order);
    }
}
