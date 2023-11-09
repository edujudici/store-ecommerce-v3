<?php

namespace Tests\Unit\Services;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use App\Services\Order\OrderAddressService;
use App\Services\Order\OrderCommentService;
use App\Services\Order\OrderHistoryService;
use App\Services\Order\OrderItemsService;
use App\Services\Order\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class OrderServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $orderItemsServiceMock;
    private $orderHistoryServiceMock;
    private $orderAddressServiceMock;
    private $orderCommentServiceMock;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->orderItemsServiceMock = $this->mock(OrderItemsService::class)
            ->makePartial();
        $this->orderHistoryServiceMock = $this->mock(OrderHistoryService::class)
            ->makePartial();
        $this->orderAddressServiceMock = $this->mock(OrderAddressService::class)
            ->makePartial();
        $this->orderCommentServiceMock = $this->mock(OrderCommentService::class)
            ->makePartial();

        $this->service = new OrderService(
            new Order(),
            $this->orderItemsServiceMock,
            $this->orderHistoryServiceMock,
            $this->orderAddressServiceMock,
            $this->orderCommentServiceMock
        );
    }

    /** @test  */
    public function should_find_a_item()
    {
        $order = Order::factory()->create();

        $response = $this->service->getOrderByPreference(
            $order->ord_preference_id
        );

        $this->assertInstanceOf(Order::class, $response);
        $this->assertEquals($response->ord_id, $order->ord_id);
        $this->assertEquals($response->user_id, $order->user_id);
        $this->assertEquals($response->ord_protocol, $order->ord_protocol);
        $this->assertEquals($response->ord_preference_id, $order->ord_preference_id);
        $this->assertEquals($response->ord_preference_init_point, $order->ord_preference_init_point);
        $this->assertEquals($response->ord_external_reference, $order->ord_external_reference);
        $this->assertEquals($response->ord_subtotal, $order->ord_subtotal);
        $this->assertEquals($response->ord_freight_code, $order->ord_freight_code);
        $this->assertEquals($response->ord_freight_service, $order->ord_freight_service);
        $this->assertEquals($response->ord_freight_time, $order->ord_freight_time);
        $this->assertEquals($response->ord_freight_price, $order->ord_freight_price);
        $this->assertEquals($response->ord_total, $order->ord_total);
        $this->assertEquals($response->ord_delivery_date, $order->ord_delivery_date);
        $this->assertEquals($response->ord_voucher_code, $order->ord_voucher_code);
        $this->assertEquals($response->ord_voucher_value, $order->ord_voucher_value);
        $this->assertEquals($response->ord_promised_date, $order->ord_promised_date);
        $this->assertEquals($response->ord_promised_date_recalculated, $order->ord_promised_date_recalculated);
    }

    /** @test  */
    public function should_list_items()
    {
        $user = User::factory()->create();
        Order::factory()->count(3)->create(['user_id' => $user->id]);

        $request = Request::create('/', 'POST', [
            'uuid' => $user->uuid,
        ]);

        $response = $this->service->index($request);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('orders', $response);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertCount(3, $response['orders']);
    }

    /** @test  */
    public function should_store_item()
    {
        $cart = $this->mockCart();
        $preference = $this->mockPreference($cart);
        $address = Address::factory()->create(['user_id' => $cart['user']->id]);

        $this->orderItemsServiceMock->shouldReceive('store')
            ->once();
        $this->orderAddressServiceMock->shouldReceive('store')
            ->once();
        $this->orderCommentServiceMock->shouldReceive('storeMessageWelcome')
            ->once();

        $response = $this->service->create($preference, $cart, $address, true);

        $this->assertInstanceOf(Order::class, $response);
        $this->assertNotNull($response->ord_id);
        $this->assertNotNull($response->ord_protocol);
        $this->assertEquals($preference['id'], $response->ord_preference_id);
        $this->assertEquals($preference['shipments']['cost'], $response->ord_freight_price);
        $this->assertEquals($cart['freightData']['code'], $response->ord_freight_code);
        $this->assertEquals($cart['freightData']['serviceName'], $response->ord_freight_service);
        $this->assertEquals($cart['freightData']['deliveryTime'], $response->ord_freight_time);
        $this->assertEquals($cart['subtotal'], $response->ord_subtotal);
        $this->assertEquals($cart['total'], $response->ord_total);
    }

    /** @test  */
    public function should_store_new_order()
    {
        $user = User::factory()->create();
        $request = Request::create('/', 'POST', [
            'preferenceId' => 'abc-123',
            'status' => Order::STATUS_NEW,
            'user_id' => $user->id,
        ]);

        $this->orderHistoryServiceMock->shouldReceive('store')
            ->once();

        $this->orderCommentServiceMock->shouldReceive('storeMessageOrder')
            ->once();

        $response = $this->service->store($request);

        $this->assertInstanceOf(Order::class, $response);
        $this->assertEquals('abc-123', $response->ord_preference_id);
    }

    /** @test  */
    public function should_store_paid_order()
    {
        $user = User::factory()->create();
        $request = Request::create('/', 'POST', [
            'preferenceId' => 'abc-123',
            'status' => Order::STATUS_PAID,
            'approvedDate' => date('Y-m-d H:i:s'),
            'user_id' => $user->id
        ]);

        $this->orderHistoryServiceMock->shouldReceive('store')
            ->once();

        $this->orderCommentServiceMock->shouldReceive('storeMessageWelcome')
            ->once();

        $response = $this->service->store($request);

        $this->assertInstanceOf(Order::class, $response);
        $this->assertEquals('abc-123', $response->ord_preference_id);
        $this->assertNotEmpty($response->ord_promised_date_recalculated);
    }

    /** @test  */
    public function should_store_complete_order()
    {
        $user = User::factory()->create();
        $request = Request::create('/', 'POST', [
            'preferenceId' => 'abc-123',
            'status' => Order::STATUS_COMPLETE,
            'user_id' => $user->id
        ]);

        $this->orderHistoryServiceMock->shouldReceive('store')
            ->once();

        $this->orderCommentServiceMock->shouldReceive('storeMessageFinished')
            ->once();

        $response = $this->service->store($request);

        $this->assertInstanceOf(Order::class, $response);
        $this->assertEquals('abc-123', $response->ord_preference_id);
        $this->assertNotNull($response->ord_delivery_date);
    }

    private function mockPreference($cart)
    {
        return [
            'id' => $this->faker->randomNumber(9),
            'init_point' => $this->faker->url,
            'external_reference' => 'abc-123',
            'items' => [],
            'shipments' => [
                'cost' => str_replace(
                    ',',
                    '.',
                    $cart['freightData']['price']
                ),
            ],
        ];
    }

    private function mockCart(): array
    {
        $user = User::factory()->create();
        return [
            'user' => $user,
            'freightData' => [
                'code' => $this->faker->randomNumber(2),
                'serviceName' => $this->faker->title,
                'deliveryTime' => $this->faker->randomNumber(2),
                'price' => '9,99',
            ],
            'subtotal' => $this->faker->randomFloat(2, 1, 100),
            'total' => $this->faker->randomFloat(2, 1, 100),
            'voucher' => $this->faker->sentence,
            'voucherValue' => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}
