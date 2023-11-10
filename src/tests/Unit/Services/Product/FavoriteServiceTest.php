<?php

namespace Tests\Unit\Services\Product;

use App\Exceptions\BusinessError;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\UserSession;
use App\Services\Product\FavoriteService;
use App\Services\Product\ProductService;
use App\Services\User\UserSessionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class FavoriteServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $productServiceMock;
    private $userSessionServiceMock;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->productServiceMock = $this->mock(ProductService::class)
            ->makePartial();
        $this->userSessionServiceMock = $this->mock(UserSessionService::class)
            ->makePartial();
        $this->service = new FavoriteService(
            $this->productServiceMock,
            $this->userSessionServiceMock
        );
    }

    /** @test  */
    public function should_list_items_empty()
    {
        $user = User::factory()->create();

        $this->userSessionServiceMock->shouldReceive('findByUser')
            ->once()
            ->andReturn(null);

        $response = $this->service->index($user->id);
        $this->assertEmpty($response);
    }

    /** @test  */
    public function should_list_items()
    {
        $user = User::factory()->create();
        $userSession = UserSession::factory()->create(['user_id' => $user->id]);

        $this->userSessionServiceMock->shouldReceive('findByUser')
            ->once()
            ->andReturn($userSession);

        $response = $this->service->index($user->id);

        $this->assertIsArray($response);
        $this->assertEquals(json_decode($userSession->uss_json, true), $response);
    }

    /** @test  */
    public function should_find_a_item()
    {
        $product = $this->mockSession();

        $response = $this->service->getFavoriteById($product['sku']);

        $this->assertIsArray($response);
        $this->assertEquals($product['sku'], $response['sku']);
        $this->assertEquals($product['title'], $response['title']);
        $this->assertEquals($product['price'], $response['price']);
        $this->assertEquals($product['image'], $response['image']);
        $this->assertEquals($product['thumbnail'], $response['thumbnail']);
        $this->assertEquals($product['date'], $response['date']);
        $this->assertEquals($product['amount'], $response['amount']);
    }

    /** @test  */
    public function should_store_item()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();

        $request = Request::create('/', 'POST', [
            'sku' => $product->pro_sku,
            'amount' => 1
        ]);

        $this->productServiceMock->shouldReceive('findBySku')
            ->once()
            ->andReturn($product);

        $this->userSessionServiceMock->shouldReceive('store')
            ->once()
            ->andReturn(new UserSession());

        $this->service->addFavorite($request);

        $key = 'favorite.products.' . $product->pro_sku;
        $sessionData = session($key);

        $this->assertTrue(is_array($sessionData));
        $this->assertEquals($sessionData['sku'], $product->pro_sku);
        $this->assertEquals($sessionData['title'], $product->pro_title);
        $this->assertEquals($sessionData['price'], $product->pro_price);
        $this->assertEquals($sessionData['image'], $product->pro_image);
        $this->assertEquals($sessionData['amount'], $request->input('amount'));

        session()->forget($key);
    }

    /** @test  */
    public function should_update_item()
    {
        $this->expectException(BusinessError::class);

        $product = $this->mockSession();

        $request = Request::create('/', 'POST', [
            'sku' => $product['sku'],
        ]);

        $this->service->addFavorite($request);
    }

    /** @test  */
    public function should_destroy_item()
    {
        $product = $this->mockSession();

        $request = Request::create('/', 'POST', [
            'sku' => $product['sku'],
        ]);

        $this->userSessionServiceMock->shouldReceive('store')
            ->once()
            ->andReturn(new UserSession());

        $this->service->destroy($request);

        $sessionData = session('favorite.products.' . $product['sku']);

        $this->assertNull($sessionData);
    }

    private function mockSession(): array
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();

        $prodAtributes = [
            'sku' => $product->pro_sku,
            'title' => $product->pro_title,
            'price' => $product->pro_price,
            'image' => $product->pro_image,
            'thumbnail' => $product->pro_thumbnail,
            'date' => date('Y-m-d H:i:s'),
            'amount' => 1,
        ];
        $this->withSession(['favorite.products.' . $product->pro_sku => $prodAtributes]);

        return $prodAtributes;
    }
}
