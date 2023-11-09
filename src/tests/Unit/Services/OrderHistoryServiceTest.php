<?php

namespace Tests\Unit\Services;

use App\Models\Order;
use App\Services\Order\OrderHistoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class OrderHistoryServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new OrderHistoryService();
    }

    public function test_save()
    {
        $order = Order::factory()->create();

        $request = [
            'ord_id' =>$this->faker->randomNumber(9),
            'orh_preference_id' =>$this->faker->randomNumber(9),
            'orh_collection_status' => 'new',
        ];

        $this->service->store($order, $request);

        $this->assertCount(1, $order->histories);
        $this->assertInstanceOf(Collection::class, $order->histories);
    }
}
