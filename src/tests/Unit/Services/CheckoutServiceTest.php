<?php

namespace Tests\Unit\Services;

use App\Models\Address;
use App\Models\User;
use App\Services\Order\CartService;
use App\Services\Order\CheckoutService;
use App\Services\Order\OrderService;
use App\Services\Order\PreferenceService;
use App\Services\User\AddressService;
use App\Services\User\VoucherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use MercadoPago\Item;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class CheckoutServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $cartServiceMock;
    private $addressServiceMock;
    private $PreferenceServiceMock;
    private $orderServiceMock;
    private $voucherServiceMock;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->cartServiceMock = $this->mock(CartService::class)
            ->makePartial();
        $this->addressServiceMock = $this->mock(AddressService::class)
            ->makePartial();
        $this->PreferenceServiceMock = $this->mock(PreferenceService::class)
            ->makePartial();
        $this->orderServiceMock = $this->mock(OrderService::class)
            ->makePartial();
        $this->voucherServiceMock = $this->mock(VoucherService::class)
            ->makePartial();

        $this->service = new CheckoutService(
            $this->cartServiceMock,
            $this->addressServiceMock,
            $this->PreferenceServiceMock,
            $this->orderServiceMock,
            $this->voucherServiceMock
        );
    }

    /** @test  */
    public function should_store_item()
    {
        $request = Request::create('/', 'POST', [
            'address' => []
        ]);

        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);
        $preference = $this->mockPreference();

        $this->cartServiceMock->shouldReceive('index')
            ->once()
            ->andReturn($this->mockCart(1));

        $this->voucherServiceMock->shouldReceive('applyVoucher')
            ->once()
            ->andReturn([
                'user' => $user,
            ]);

        $this->addressServiceMock->shouldReceive('store')
            ->once()
            ->andReturn($address);

        $this->PreferenceServiceMock->shouldReceive('create')
            ->once()
            ->andReturn($preference);

        $this->orderServiceMock->shouldReceive('create')
            ->once();

        $response = $this->service->store($request);

        $this->assertEquals($preference['init_point'], $response);
    }

    /** @test  */
    public function should_store_item_status_paid()
    {
        $request = Request::create('/', 'POST', [
            'address' => []
        ]);

        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);
        $preference = $this->mockPreference();

        $this->cartServiceMock->shouldReceive('index')
            ->once()
            ->andReturn($this->mockCart(0));

        $this->voucherServiceMock->shouldReceive('applyVoucher')
            ->once()
            ->andReturn([
                'user' => $user,
            ]);

        $this->addressServiceMock->shouldReceive('store')
            ->once()
            ->andReturn($address);

        $this->orderServiceMock->shouldReceive('create')
            ->once();

        $response = $this->service->store($request);

        $this->assertStringContainsString(
            "http://localhost/payment/success?preference_id=",
            $response
        );
    }

    private function mockPreference()
    {
        return [
            'id' => $this->faker->randomNumber(9),
            'init_point' => $this->faker->url()
        ];
    }

    private function getItems(): array
    {
        $item = new Item();
        $item->id = $this->faker->randomNumber(9);
        $item->title = $this->faker->title;
        $item->quantity = $this->faker->randomNumber(2);
        $item->currency_id = 'BRL';
        $item->unit_price = $this->faker->randomFloat(2, 1, 3);
        return [$item];
    }

    private function mockCart($total): array
    {
        return [
            'total' => $total,
            'user' => []
        ];
    }
}
