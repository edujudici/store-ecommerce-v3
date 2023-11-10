<?php

namespace Tests\Unit\Services\Payment;

use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Services\Order\OrderService;
use App\Services\Payment\PayResponseService;
use App\Services\Payment\PayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class PayResponseServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $orderServiceMock;
    private $payServiceMock;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->orderServiceMock = $this->mock(OrderService::class)
            ->makePartial();

        $this->payServiceMock = $this->mock(PayService::class)
            ->makePartial();

        $this->service = new PayResponseService(
            $this->orderServiceMock
        );
    }

    /** @test  */
    public function should_response_payment()
    {
        $order = Order::factory()->create();
        OrderItem::factory()->count(2)->create([
            'ord_id' => $order->ord_id,
        ]);
        OrderAddress::factory()->count(2)->create([
            'ord_id' => $order->ord_id,
        ]);

        $request = Request::create('/', 'POST', [
            'preference_id' => $order->ord_preference_id,
        ]);

        $this->orderServiceMock->shouldReceive('getOrderByPreference')
            ->once()
            ->andReturn($order);

        $response = $this->service->confirmation($request);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('order', $response);
        $this->assertArrayHasKey('items', $response);
        $this->assertArrayHasKey('address', $response);
    }

    /** @test  */
    public function should_notification_ipn()
    {
        $request = Request::create('/', 'POST', [
            'id' => $this->faker->randomNumber(9),
            'topic' => 'merchant_order',
        ]);

        $this->payServiceMock->shouldReceive('processNotification')
            ->once();

        $this->service->notification($request);
    }
}
