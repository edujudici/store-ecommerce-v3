<?php

namespace Tests\Unit\Services;

use App\Exceptions\BusinessError;
use App\Models\Voucher;
use App\Services\VoucherService;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class VoucherServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new VoucherService(new Voucher());
    }

    /** @test  */
    public function should_list_items()
    {
        Voucher::factory()->count(3)->create();

        $response = $this->service->index();

        $this->assertArrayHasKey('vouchers', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertInstanceOf(Collection::class, $response['vouchers']);
        $this->assertIsArray($response['status']);
        $this->assertCount(3, $response['vouchers']);
    }

    /** @test  */
    public function should_find_a_item_by_user()
    {
        $user = User::factory()->create();
        Voucher::factory()->count(3)->create([
            'user_uuid' => $user->uuid
        ]);

        $request = Request::create('/', 'POST', [
            'uuid' => $user->uuid,
        ]);

        $response = $this->service->findByUser($request);

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_find_a_item()
    {
        $voucher = Voucher::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $voucher->vou_id,
        ]);

        $response = $this->service->findById($request);

        $this->assertInstanceOf(Voucher::class, $response);
        $this->assertEquals($response->vou_id, $voucher->vou_id);
        $this->assertEquals($response->vou_id_base, $voucher->vou_id_base);
        $this->assertEquals($response->user_uuid, $voucher->user_uuid);
        $this->assertEquals($response->vou_code, $voucher->vou_code);
        $this->assertEquals($response->vou_value, $voucher->vou_value);
        $this->assertEquals($response->vou_expiration_date, $voucher->vou_expiration_date);
        $this->assertEquals($response->vou_applied_date, $voucher->vou_applied_date);
        $this->assertEquals($response->vou_description, $voucher->vou_description);
        $this->assertEquals($response->vou_status, $voucher->vou_status);
    }

    /** @test  */
    public function should_store_item()
    {
        $request = Request::create('/', 'POST', [
            'user_uuid' => $this->faker->uuid,
            'vou_value' => $this->faker->randomFloat(2, 1, 100),
            'vou_expiration_date' => $this->faker->date(),
            'vou_description' => 'voucher unit test',
            'vou_status' => 'active',
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(Voucher::class, $response);
        $this->assertNotNull($response->vou_id);
        $this->assertNotNull($response->vou_code);
        $this->assertEquals($request->input('user_uuid'), $response->user_uuid);
        $this->assertEquals($request->input('vou_value'), $response->vou_value);
        $this->assertEquals($request->input('vou_expiration_date'), $response->vou_expiration_date);
        $this->assertEquals($request->input('vou_description'), $response->vou_description);
        $this->assertEquals($request->input('vou_status'), $response->vou_status);
    }

    /** @test  */
    public function should_update_item()
    {
        $voucher = Voucher::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $voucher->vou_id,
            'vou_description' => $voucher->vou_description,
            'vou_value' => $this->faker->randomFloat(2, 1, 100),
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(Voucher::class, $response);
        $this->assertEquals($request->input('id'), $response->vou_id);
        $this->assertEquals($request->input('vou_description'), $response->vou_description);
        $this->assertEquals($request->input('vou_value'), $response->vou_value);
    }

    /** @test  */
    public function should_destroy_item()
    {
        $voucher = Voucher::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $voucher->vou_id,
        ]);

        $response = $this->service->destroy($request);

        $this->assertTrue($response);
    }

    /** @test  */
    public function should_valid_voucher_invalid()
    {
        $this->expectException(BusinessError::class);

        $request = Request::create('/', 'POST', [
            'code' => 'code_invalid',
        ]);

        $this->service->valid($request);
    }

    /** @test  */
    public function should_valid_voucher_used()
    {
        $this->expectException(BusinessError::class);

        $voucher = Voucher::factory()->create();

        $request = Request::create('/', 'POST', [
            'code' => $voucher->vou_code,
        ]);

        $this->service->valid($request);
    }

    /** @test  */
    public function should_valid_voucher_expired()
    {
        $this->expectException(BusinessError::class);

        $voucher = Voucher::factory()->create([
            'vou_applied_date' => null,
            'vou_expiration_date' => date('2020-01-01')
        ]);

        $request = Request::create('/', 'POST', [
            'code' => $voucher->vou_code,
        ]);

        $this->service->valid($request);
    }

    /** @test  */
    public function should_valid_voucher()
    {
        $voucher = Voucher::factory()->create([
            'vou_applied_date' => null,
            'vou_expiration_date' => null
        ]);

        $request = Request::create('/', 'POST', [
            'code' => $voucher->vou_code,
        ]);

        $response = $this->service->valid($request);

        $this->assertInstanceOf(Voucher::class, $response);
        $this->assertEquals($response->vou_id, $voucher->vou_id);
        $this->assertEquals($response->vou_id_base, $voucher->vou_id_base);
        $this->assertEquals($response->user_uuid, $voucher->user_uuid);
        $this->assertEquals($response->vou_code, $voucher->vou_code);
        $this->assertEquals($response->vou_value, $voucher->vou_value);
        $this->assertEquals($response->vou_expiration_date, $voucher->vou_expiration_date);
        $this->assertEquals($response->vou_applied_date, $voucher->vou_applied_date);
        $this->assertEquals($response->vou_description, $voucher->vou_description);
        $this->assertEquals($response->vou_status, $voucher->vou_status);
    }

    /** @test  */
    public function should_apply_voucher()
    {
        $cart = $this->mockCart(50, 100);

        $request = Request::create('/', 'POST', []);

        $this->service->applyVoucher($cart, $request);

        $this->assertEquals($cart['products'][0]['price'], 100);
        $this->assertEquals($cart['products'][0]['newPrice'], 50);
    }

    /** @test  */
    public function should_apply_voucher_save_remaining_value()
    {
        $cart = $this->mockCart(100, 20, 3);

        $request = Request::create('/', 'POST', []);

        $this->service->applyVoucher($cart, $request);

        $this->assertEquals($cart['products'][0]['price'], 20);
        $this->assertEquals($cart['products'][0]['newPrice'], 0);
        $this->assertEquals($cart['products'][1]['price'], 20);
        $this->assertEquals($cart['products'][1]['newPrice'], 0);
        $this->assertEquals($cart['products'][2]['price'], 20);
        $this->assertEquals($cart['products'][2]['newPrice'], 0);

        $request = Request::create('/', 'POST', [
            'uuid' => $cart['user']->uuid,
        ]);
        $response = $this->service->findByUser($request);
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(1, $response);
        $this->assertEquals($response[0]->vou_value, 40);
    }

    private function mockCart($voucherValue, $productValue, $productsTotal = 1): array
    {
        $user = User::factory()->create();
        $voucher = Voucher::factory()->create([
            'vou_applied_date' => null,
            'vou_expiration_date' => null,
            'vou_value' => $voucherValue,
        ]);
        $data = [
            'user' => $user,
            'subtotal' => 0,
            'voucher' => $voucher->vou_code,
            'products' => []
        ];

        for ($i = 0; $i < $productsTotal; $i++) {
            $data['products'][] = [
                'price' => $productValue
            ];
            $data['subtotal'] += $productValue;
        }
        return $data;
    }
}
