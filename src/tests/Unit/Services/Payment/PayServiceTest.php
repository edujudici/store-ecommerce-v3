<?php

namespace Tests\Unit\Services\Payment;

use App\Exceptions\BusinessError;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Services\Order\OrderService;
use App\Services\Payment\PayClientInterface;
use App\Services\Payment\PayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class PayServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $orderServiceMock;
    private $service;
    private $payClientInterfaceMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->orderServiceMock = $this->mock(OrderService::class)
            ->makePartial();
        $this->payClientInterfaceMock = $this->mock(PayClientInterface::class)
            ->makePartial();

        $this->service = new PayService(
            $this->orderServiceMock,
            new OrderPayment(),
            $this->payClientInterfaceMock
        );
    }

    /** @test  */
    public function should_process_notification_type_error()
    {
        $this->expectException(BusinessError::class);

        $data = [
            'type' => 'type_does_not_exists',
        ];

        $this->service->processNotification($data);
    }

    /** @test  */
    public function should_process_notification_payment_not_found()
    {
        $this->expectException(BusinessError::class);
        $this->expectExceptionMessage('Payment not found');

        $data = [
            'data' => [
                'id' => $this->faker->randomNumber(9)
            ],
            'type' => 'payment',
        ];

        $this->payClientInterfaceMock->shouldReceive('getPaymentClient')
            ->once()
            ->andReturn(new MockPayClient(new MockPayClientResponse(null)));

        $this->service->processNotification($data);
    }

    /** @test  */
    public function should_process_notification_payment_error()
    {
        $this->expectException(BusinessError::class);
        $this->expectExceptionMessage('Payment error: 400');

        $data = [
            'data' => [
                'id' => $this->faker->randomNumber(9)
            ],
            'type' => 'payment',
        ];

        $this->payClientInterfaceMock->shouldReceive('getPaymentClient')
            ->once()
            ->andReturn(new MockPayClient(new MockPayClientResponse(new MockPayClientStatus(400, null))));

        $this->service->processNotification($data);
    }

    /** @test  */
    public function should_process_notification_payment_order_not_found()
    {
        $this->expectException(BusinessError::class);
        $this->expectExceptionMessage('Payment order not found');

        $data = [
            'data' => [
                'id' => $this->faker->randomNumber(9)
            ],
            'type' => 'payment',
        ];

        $this->payClientInterfaceMock->shouldReceive('getPaymentClient')
            ->once()
            ->andReturn(new MockPayClient(new MockPayClientResponse(new MockPayClientStatus(200, [
                'order' => null
            ]))));

        $this->service->processNotification($data);
    }

    /** @test  */
    public function should_process_notification_merchant_not_found()
    {
        $this->expectException(BusinessError::class);
        $this->expectExceptionMessage('Merchant order not found');

        $data = [
            'data' => [
                'id' => $this->faker->randomNumber(9)
            ],
            'type' => 'payment',
        ];
        $this->payClientInterfaceMock->shouldReceive('getPaymentClient')
            ->once()
            ->andReturn(new MockPayClient(new MockPayClientResponse(new MockPayClientStatus(200, [
                'order' => ['id' => 1]
            ]))));

        $this->payClientInterfaceMock->shouldReceive('getMerchantOrderClient')
            ->once()
            ->andReturn(new MockPayClient(new MockPayClientResponse(null)));

        $this->service->processNotification($data);
    }

    /** @test  */
    public function should_process_notification_merchant_error()
    {
        $this->expectException(BusinessError::class);
        $this->expectExceptionMessage('Merchant error: 400');

        $data = [
            'data' => [
                'id' => $this->faker->randomNumber(9)
            ],
            'type' => 'payment',
        ];
        $this->payClientInterfaceMock->shouldReceive('getPaymentClient')
            ->once()
            ->andReturn(new MockPayClient(new MockPayClientResponse(new MockPayClientStatus(200, [
                'order' => ['id' => 1]
            ]))));

        $this->payClientInterfaceMock->shouldReceive('getMerchantOrderClient')
            ->once()
            ->andReturn(new MockPayClient(new MockPayClientResponse(new MockPayClientStatus(400, null))));

        $this->service->processNotification($data);
    }

    /** @test  */
    public function should_process_notification_payment_success()
    {
        $data = [
            'data' => [
                'id' => $this->faker->randomNumber(9)
            ],
            'type' => 'payment',
        ];
        $mockPayment = $this->mockPayment(1);
        $this->payClientInterfaceMock->shouldReceive('getPaymentClient')
            ->once()
            ->andReturn(new MockPayClient(new MockPayClientResponse(new MockPayClientStatus(200, $mockPayment))));

        $this->payClientInterfaceMock->shouldReceive('getMerchantOrderClient')
            ->once()
            ->andReturn(new MockPayClient(new MockPayClientResponse(new MockPayClientStatus(200, [
                'preference_id' => 'abc123'
            ]))));

        $order = Order::factory()->create([
            'ord_id' => $mockPayment['order']['id'],
        ]);
        $this->orderServiceMock->shouldReceive('store')
            ->once()
            ->andReturn($order);

        $this->service->processNotification($data);

        $payment = $order->payments[0];

        $this->assertEquals($payment->ord_id, $mockPayment['order']['id']);
        $this->assertEquals($payment->orp_payment_id, $mockPayment['id']);
        $this->assertEquals($payment->orp_order_id, $mockPayment['order']['id']);
        $this->assertEquals($payment->orp_payer_id, $mockPayment['payer']['id']);
        $this->assertEquals($payment->orp_payer_email, $mockPayment['payer']['email']);
        $this->assertEquals($payment->orp_payer_first_name, $mockPayment['payer']['first_name']);
        $this->assertEquals($payment->orp_payer_last_name, $mockPayment['payer']['last_name']);
        $this->assertEquals($payment->orp_payer_phone, $mockPayment['payer']['phone']['number']);
        $this->assertEquals($payment->orp_payment_method_id, $mockPayment['payment_method_id']);
        $this->assertEquals($payment->orp_payment_type_id, $mockPayment['payment_type_id']);
        $this->assertEquals($payment->orp_status, $mockPayment['status']);
        $this->assertEquals($payment->orp_status_detail, $mockPayment['status_detail']);
        $this->assertEquals($payment->orp_transaction_amount, $mockPayment['transaction_amount']);
        $this->assertEquals($payment->orp_received_amount, $mockPayment['transaction_details']['net_received_amount']);
        $this->assertEquals($payment->orp_resource_url, $mockPayment['transaction_details']['external_resource_url']);
        $this->assertEquals($payment->orp_total_paid_amount, $mockPayment['transaction_details']['total_paid_amount']);
        $this->assertEquals($payment->orp_shipping_amount, $mockPayment['shipping_amount']);
        $this->assertEquals($payment->orp_date_approved, date('Y-m-d H:i:s', strtotime($mockPayment['date_approved'])));
        $this->assertEquals($payment->orp_date_created, date('Y-m-d H:i:s', strtotime($mockPayment['date_created'])));
        $this->assertEquals($payment->orp_date_of_expiration, date('Y-m-d H:i:s', strtotime($mockPayment['date_of_expiration'])));
        $this->assertEquals($payment->orp_live_mode, $mockPayment['live_mode']);
    }

    private function mockPayment($orderId = null): array
    {
        return [
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
    }
}

/** Internal classes to assist with unit testing */

class MockPayClient
{
    private $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function get()
    {
        return $this->response;
    }
}

class MockPayClientResponse
{
    private $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}

class MockPayClientStatus
{
    private $statusCode;
    private $content;

    public function __construct($statusCode, $content)
    {
        $this->statusCode = $statusCode;
        $this->content = $content;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getContent()
    {
        return $this->content;
    }
}
