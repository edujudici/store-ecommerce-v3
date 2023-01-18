<?php

namespace Tests\Unit\Services;

use App\Models\Order;
use App\Services\OrderItemsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class OrderItemsServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new OrderItemsService();
    }

    /** @test  */
    public function should_store_item()
    {
        $order = Order::factory()->create();
        $cart = [
            'products' => [
                [
                    'id' => $this->faker->randomNumber(9),
                    'sku' => $this->faker->randomNumber(9),
                    'quantity' => $this->faker->randomNumber(3),
                    'price' => $this->faker->randomFloat(2, 1, 100),
                    'title' => $this->faker->title,
                    'amount' => $this->faker->randomNumber(2),
                ],
                [
                    'id' => $this->faker->randomNumber(9),
                    'sku' => $this->faker->randomNumber(9),
                    'quantity' => $this->faker->randomNumber(3),
                    'price' => $this->faker->randomFloat(2, 1, 100),
                    'title' => $this->faker->title,
                    'amount' => $this->faker->randomNumber(2),
                ],
            ],
        ];

        $this->service->store($order, $cart);

        $this->assertInstanceOf(Collection::class, $order->items);
        $this->assertCount(2, $order->items);
    }
}
