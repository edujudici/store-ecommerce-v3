<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class OrderAddressTest extends TestCase
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
    public function orders_address_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('orders_address', [
                'ora_id', 'ord_id', 'ora_name', 'ora_surname', 'ora_phone',
                'ora_zipcode', 'ora_address', 'ora_number', 'ora_district',
                'ora_city', 'ora_complement', 'ora_type', 'ora_uf',
            ]),
            1
        );
    }

    /** @test */
    public function a_order_address_belongs_to_a_order()
    {
        $orderAddress = OrderAddress::factory()
            ->create(['ord_id' => $this->order->ord_id]);

        $this->assertInstanceOf(Order::class, $orderAddress->order);
    }
}
