<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderComment;
use App\Models\OrderHistory;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class OrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $order;

    public function setUp() :void
    {
        parent::setUp();

        $this->order = Order::factory()
            ->for(User::factory())
            ->create();
    }

    /** @test */
    public function orders_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('orders', [
                'ord_id', 'user_id', 'ord_protocol', 'ord_preference_id',
                'ord_preference_init_point', 'ord_external_reference',
                'ord_subtotal', 'ord_freight_code', 'ord_freight_service',
                'ord_freight_time', 'ord_freight_price', 'ord_total',
                'ord_delivery_date', 'ord_voucher_code', 'ord_voucher_value',
                'ord_promised_date', 'ord_promised_date_recalculated',
            ]),
            1
        );
    }

    /** @test */
    public function a_order_belongs_to_a_user()
    {
        $this->assertInstanceOf(User::class, $this->order->user);
    }

    /** @test */
    public function a_order_has_many_items()
    {
        OrderItem::factory()
            ->count(3)
            ->for($this->order)
            ->create();

        $this->assertCount(3, $this->order->items);
        $this->assertInstanceOf(Collection::class, $this->order->items);
    }

    /** @test */
    public function a_order_has_many_histories()
    {
        OrderHistory::factory()
            ->count(3)
            ->for($this->order)
            ->create();

        $this->assertCount(3, $this->order->histories);
        $this->assertInstanceOf(Collection::class, $this->order->histories);
    }

    /** @test */
    public function a_order_has_many_comments()
    {
        $orderComment = OrderComment::factory()
            ->count(3)
            ->for($this->order)
            ->create();

        $this->assertCount(3, $this->order->comments);
        $this->assertInstanceOf(Collection::class, $this->order->comments);
    }

    /** @test */
    public function a_order_has_a_address()
    {
        OrderAddress::factory()
            ->create(['ord_id' => $this->order->ord_id]);

        $this->assertInstanceOf(OrderAddress::class, $this->order->address);
        $this->assertEquals(1, $this->order->address->count());
    }

    /** @test */
    public function get_status()
    {
        $status = $this->order::getStatus();
        $this->assertIsArray($status);
        $this->assertContains("Em produção", $status);
        $this->assertContains("Em transporte", $status);
        $this->assertContains("Concluído", $status);
        $this->assertContains("Cancelar", $status);
    }

    /** @test */
    public function get_all_status()
    {
        $status = $this->order::getAllStatus();
        $this->assertIsArray($status);
        $this->assertContains('Novo', $status);
        $this->assertContains('Processando Pagamento', $status);
        $this->assertContains('Pago', $status);
        $this->assertContains('Em produção', $status);
        $this->assertContains('Em transporte', $status);
        $this->assertContains('Concluído', $status);
        $this->assertContains('Cancelado', $status);
    }
}
