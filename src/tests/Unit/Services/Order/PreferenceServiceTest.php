<?php

namespace Tests\Unit\Services\Order;

use App\Exceptions\BusinessError;
use App\Models\Address;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\Order\PreferenceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class PreferenceServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new PreferenceService();
    }

    /** @test  */
    public function should_create_preference()
    {
        $cart = $this->mockCart();
        $address = Address::factory()->create(['user_id' => $cart['user']->id]);

        $response = $this->service->create($address, $cart);

        $this->assertIsArray($response);
        $this->assertNotNull($response['id']);
        $this->assertNotNull($response['init_point']);
    }

    /** @test  */
    public function should_create_preference_exception()
    {
        $this->expectException(BusinessError::class);

        $cart = $this->mockCartPriceZero();
        $address = Address::factory()->create(['user_id' => $cart['user']->id]);

        $this->service->create($address, $cart);
    }

    private function mockCart(): array
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();
        $user = User::factory()->create();

        return [
            'user' => $user,
            'products' => [
                [
                    'id' => $product->pro_id,
                    'title' => $product->pro_title,
                    'price' => $product->pro_price,
                    'image' => $product->pro_image,
                    'thumbnail' => $product->pro_thumbnail,
                    'amount' => 1,
                ],
            ],
            'freightData' => [
                'price' => $this->faker->randomFloat(2, 1, 100),
                'serviceName' => 'Pac',
            ],
        ];
    }

    private function mockCartPriceZero(): array
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();
        $user = User::factory()->create();

        return [
            'user' => $user,
            'products' => [
                [
                    'id' => $product->pro_id,
                    'title' => $product->pro_title,
                    'price' => 0,
                    'image' => $product->pro_image,
                    'thumbnail' => $product->pro_thumbnail,
                    'amount' => 1,
                ],
            ],
            'freightData' => [
                'price' => 0,
                'serviceName' => 'Pac',
            ],
        ];
    }
}
