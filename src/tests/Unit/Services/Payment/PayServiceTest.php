<?php

namespace Tests\Unit\Services\Payment;

use App\Exceptions\BusinessError;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Services\Order\OrderService;
use App\Services\Payment\PayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use MercadoPago\MerchantOrder;
use MercadoPago\Payment;
use MercadoPago\SDK;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class PayServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $orderServiceMock;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->orderServiceMock = $this->mock(OrderService::class)
            ->makePartial();

        $this->service = new PayService(
            $this->orderServiceMock,
            new OrderPayment()
        );
    }

    /** @test  */
    public function should_process_notification_type_error()
    {
        $this->expectException(BusinessError::class);

        SDK::setAccessToken(env('MERCADO_PAGO_TOKEN'));

        $data = [
            'type' => 'type_does_not_exists',
        ];

        $this->service->processNotification($data);
    }

    /** @test  */
    public function should_process_notification_payment_not_found()
    {
        $this->expectException(BusinessError::class);

        SDK::setAccessToken(env('MERCADO_PAGO_TOKEN'));

        $data = [
            'data' => [
                'id' => $this->faker->randomNumber(9)
            ],
            'type' => 'payment',
        ];
        $paymentMock = $this->mock(Payment::class)
            ->makePartial();
        $paymentMock->shouldReceive('find_by_id')
            ->once()
            ->andReturn(null);

        $this->service->payment = $paymentMock;
        $this->service->processNotification($data);
    }

    /** @test  */
    public function should_process_notification_merchant_not_found()
    {
        $this->expectException(BusinessError::class);

        SDK::setAccessToken(env('MERCADO_PAGO_TOKEN'));

        $data = [
            'data' => [
                'id' => $this->faker->randomNumber(9)
            ],
            'type' => 'payment',
        ];
        $payment = $this->mockPayment();
        $paymentMock = $this->mock(Payment::class)
            ->makePartial();
        $paymentMock->shouldReceive('find_by_id')
            ->once()
            ->andReturn($payment);

        $merchantOrderMock = $this->mock(MerchantOrder::class)
            ->makePartial();
        $merchantOrderMock->shouldReceive('find_by_id')
            ->once()
            ->andReturn(null);

        $this->service->merchantOrder = $merchantOrderMock;
        $this->service->payment = $paymentMock;
        $this->service->processNotification($data);
    }

    /** @test  */
    public function should_process_notification_sucess()
    {
        SDK::setAccessToken(env('MERCADO_PAGO_TOKEN'));

        $data = [
            'data' => [
                'id' => $this->faker->randomNumber(9),
            ],
            'type' => 'payment',
        ];
        $order = Order::factory()->create();

        $merchantOrder = $this->mockMerchantOrder($order->ord_preference_id);
        $merchantOrderMock = $this->mock(MerchantOrder::class)
            ->makePartial();
        $merchantOrderMock->shouldReceive('find_by_id')
            ->once()
            ->andReturn($merchantOrder);

        $payment = $this->mockPayment($order->ord_id);
        $paymentMock = $this->mock(Payment::class)
            ->makePartial();
        $paymentMock->shouldReceive('find_by_id')
            ->once()
            ->andReturn($payment);

        $this->orderServiceMock->shouldReceive('store')
            ->once()
            ->andReturn($order);

        $this->app->instance('MercadoPago\MerchantOrder', $merchantOrderMock);
        $this->app->instance('MercadoPago\Payment', $paymentMock);

        $this->service->merchantOrder = $merchantOrderMock;
        $this->service->payment = $paymentMock;
        $this->service->processNotification($data);
    }

    private function mockMerchantOrder($preferenceId): MerchantOrder
    {
        $data = [
            'preference_id' => $preferenceId,
        ];
        $dataObj = json_decode(json_encode($data));

        $merchantOrder = new MerchantOrder();
        foreach ($dataObj as $key => $value) {
            $merchantOrder->{$key} = $value;
        }
        return $merchantOrder;
    }

    private function mockPayment($orderId = null): Payment
    {
        $data = [
            'id' => $this->faker->randomNumber(9),
            'order' => [
                'id' => $orderId,
            ],
            'payer' => [
                'id' => $this->faker->randomNumber(9),
                'email' => $this->faker->email,
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
                'phone' => [
                    'number' => '99 99999-9999',
                ]
                ],
            'payment_method_id' => 'master',
            'payment_type_id' => 'credit_card',
            'status' => 'approved',
            'status_detail' => 'accredited',
            'transaction_amount' => $this->faker->randomFloat(2, 1, 100),
            'transaction_details' => [
                'net_received_amount' => $this->faker->randomFloat(2, 1, 100),
                'external_resource_url' => $this->faker->url,
                'total_paid_amount' => $this->faker->randomFloat(2, 1, 100),
            ],
            'shipping_amount' => $this->faker->randomFloat(2, 1, 100),
            'date_approved' => $this->faker->date(),
            'date_created' => $this->faker->date(),
            'date_of_expiration' => $this->faker->date(),
            'live_mode' => $this->faker->boolean,

        ];
        $dataObj = json_decode(json_encode($data));

        $payment = new Payment();
        foreach ($dataObj as $key => $value) {
            $payment->{$key} = $value;
        }
        return $payment;
    }
}
