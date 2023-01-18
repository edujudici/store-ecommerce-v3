<?php

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Services\CartService;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class CartServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->productServiceMock = $this->mock(ProductService::class)
            ->makePartial();
        $this->service = new CartService($this->productServiceMock);
    }

    /** @test  */
    public function should_list_items()
    {
        $response = $this->service->index();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('products', $response);
        $this->assertArrayHasKey('freightServices', $response);
        $this->assertArrayHasKey('zipcode', $response);
        $this->assertArrayHasKey('subtotal', $response);
        $this->assertArrayHasKey('freightData', $response);
        $this->assertArrayHasKey('total', $response);
    }

    /** @test  */
    public function should_find_a_item()
    {
        $product = $this->mockSession();

        $response = $this->service->getCartById($product['sku']);

        $this->assertIsArray($response);
        $this->assertEquals($product['id'], $response['id']);
        $this->assertEquals($product['title'], $response['title']);
        $this->assertEquals($product['price'], $response['price']);
        $this->assertEquals($product['image'], $response['image']);
        $this->assertEquals($product['thumbnail'], $response['thumbnail']);
        $this->assertEquals($product['amount'], $response['amount']);
    }

    /** @test  */
    public function should_store_item()
    {
        $product = Product::factory()->create();

        $request = Request::create('/', 'POST', [
            'sku' => $product->pro_sku,
            'amount' => 1
        ]);

        $this->productServiceMock->shouldReceive('findBySku')
            ->once()
            ->andReturn($product);

        $this->service->store($request);

        $key = 'cart.products.' . $product->pro_sku;
        $sessionData = session($key);

        $this->assertTrue(is_array($sessionData));
        $this->assertEquals($sessionData['id'], $product->pro_id);
        $this->assertEquals($sessionData['sku'], $product->pro_sku);
        $this->assertEquals($sessionData['title'], $product->pro_title);
        $this->assertEquals($sessionData['price'], $product->pro_price);
        $this->assertEquals($sessionData['image'], $product->pro_image);
        $this->assertEquals($sessionData['amount'], $request->input('amount'));

        session()->forget($key);
    }

    /** @test  */
    public function should_store_more_item()
    {
        $product = $this->mockSession();

        $request = Request::create('/', 'POST', [
            'sku' => $product['sku'],
        ]);

        $this->service->store($request);

        $this->assertEquals(session('cart.products.' . $product['sku'] . '.amount'), 2);
    }

    /** @test  */
    public function should_update_item()
    {
        $product = $this->mockSession();

        $request = Request::create('/', 'POST', [
            'products' => [[
                'sku' => $product['sku'],
                'amount' => 10,
            ]],
            'subtotal' => $this->faker->randomFloat(2, 1, 100),
            'zipcode' => $this->faker->postcode,
            'freightServices' => [
                [
                    'code' => '04014',
                    'price' => '9,99',
                    'deliveryTime' => '10',
                    'serviceName' => 'Sedex',
                ],
            ],
            'freightSelected' => '04014',
        ]);

        $response = $this->service->update($request);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('products', $response);
        $this->assertArrayHasKey('freightServices', $response);
        $this->assertArrayHasKey('zipcode', $response);
        $this->assertArrayHasKey('subtotal', $response);
        $this->assertArrayHasKey('freightData', $response);
        $this->assertArrayHasKey('total', $response);
        $this->assertEquals(session('cart.subtotal'), $request->input('subtotal'));
        $this->assertEquals(session('cart.zipcode'), $request->input('zipcode'));
        $this->assertEquals(session('cart.freightServices'), $request->input('freightServices'));
        $this->assertGreaterThan(0, session('cart.total'));
        $this->assertNotEmpty(session('cart.freightData'));
    }

    /** @test  */
    public function should_destroy_item()
    {
        $product = $this->mockSession();

        $request = Request::create('/', 'POST', [
            'sku' => $product['sku'],
        ]);

        $this->service->destroy($request);

        $sessionData = session('cart.products.' . $product['sku']);

        $this->assertNull($sessionData);
    }

    private function mockSession(): array
    {
        $product = Product::factory()->create();

        $prodAtributes = [
            'id' => $product->pro_id,
            'sku' => $product->pro_sku,
            'title' => $product->pro_title,
            'price' => $product->pro_price,
            'image' => $product->pro_image,
            'thumbnail' => $product->pro_thumbnail,
            'amount' => 1,
        ];
        $this->withSession(['cart.products.' . $product->pro_sku => $prodAtributes]);

        return $prodAtributes;
    }
}
