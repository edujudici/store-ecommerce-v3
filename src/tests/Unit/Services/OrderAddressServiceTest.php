<?php

namespace Tests\Unit\Services;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\User;
use App\Services\Order\OrderAddressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class OrderAddressServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new OrderAddressService();
    }

    /** @test  */
    public function should_store_item()
    {
        $order = Order::factory()->create();
        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);

        $this->service->store($order, $address);

        $this->assertInstanceOf(OrderAddress::class, $order->address);
        $this->assertEquals(1, $order->address->count());
    }
}
