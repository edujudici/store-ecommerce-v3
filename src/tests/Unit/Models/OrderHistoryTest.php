<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class OrderHistoryTest extends TestCase
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
    public function orders_history_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('orders_history', [
                'orh_id', 'ord_id', 'orh_preference_id',
                'orh_collection_status',
            ]),
            1
        );
    }

    /** @test */
    public function a_order_history_belongs_to_a_order()
    {
        $orderHistory = OrderHistory::factory()
            ->create(['ord_id' => $this->order->ord_id]);

        $this->assertInstanceOf(Order::class, $orderHistory->order);
    }
}
