<?php

namespace Tests\Unit\Services\Product;

use App\Models\Category;
use App\Models\Product;
use App\Services\Product\PictureService;
use App\Services\Product\ProductExclusiveService;
use App\Services\Product\ProductRelatedService;
use App\Services\Product\ProductService;
use App\Services\Product\ProductSpecificationService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class ProductServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $productExclusiveServiceMock;
    private $productRelatedServiceMock;
    private $productSpecificationServiceMock;
    private $pictureServiceMock;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->productExclusiveServiceMock = $this->mock(
            ProductExclusiveService::class
        )->makePartial();
        $this->productRelatedServiceMock = $this->mock(
            ProductRelatedService::class
        )->makePartial();
        $this->productSpecificationServiceMock = $this->mock(
            ProductSpecificationService::class
        )->makePartial();
        $this->pictureServiceMock = $this->mock(
            PictureService::class
        )->makePartial();

        $this->service = new ProductService(
            new Product(),
            $this->productExclusiveServiceMock,
            $this->productRelatedServiceMock,
            $this->productSpecificationServiceMock,
            $this->pictureServiceMock
        );
    }

    /** @test  */
    public function should_list_items_format()
    {
        Product::factory()
            ->count(24)
            ->for(Category::factory())
            ->create([
                'pro_seller_id' => null
            ]);

        $request = Request::create('/', 'POST', [
            'amount' => 24,
        ]);

        $response = $this->service->indexFormat($request);

        $this->assertIsArray($response);
        $this->assertCount(6, $response);
    }

    /** @test  */
    public function should_list_items()
    {
        Product::factory()
            ->count(3)
            ->for(Category::factory())
            ->create([
                'pro_seller_id' => null
            ]);

        $request = Request::create('/', 'POST', []);

        $response = $this->service->index($request);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('products', $response);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertCount(3, $response['products']);
    }

    /** @test  */
    public function should_list_items_by_name()
    {
        $product1 = Product::factory()
            ->for(Category::factory())
            ->create();

        $request = Request::create('/', 'POST', [
            'term' => $product1->pro_sku,
        ]);

        $response = $this->service->findByName($request);

        $this->assertTrue($response->contains($product1));
        $this->assertCount(1, $response);
        $this->assertInstanceOf(Collection::class, $response);
    }

    /** @test  */
    public function should_find_a_item()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();

        $request = Request::create('/', 'POST', [
            'id' => $product->pro_id,
        ]);

        $response = $this->service->findById($request);

        $this->assertInstanceOf(Product::class, $response);
        $this->assertEquals($response->pro_id, $product->pro_id);
        $this->assertEquals($response->cat_id, $product->cat_id);
        $this->assertEquals($response->pro_price, $product->pro_price);
        $this->assertEquals($response->pro_oldprice, $product->pro_oldprice);
        $this->assertEquals($response->pro_title, $product->pro_title);
        $this->assertEquals($response->pro_description, $product->pro_description);
        $this->assertEquals($response->pro_description_long, $product->pro_description_long);
        $this->assertEquals($response->pro_image, $product->pro_image);
        $this->assertEquals($response->pro_sku, $product->pro_sku);
        $this->assertEquals($response->pro_seller_id, $product->pro_seller_id);
        $this->assertEquals($response->pro_category_id, $product->pro_category_id);
        $this->assertEquals($response->pro_condition, $product->pro_condition);
        $this->assertEquals($response->pro_permalink, $product->pro_permalink);
        $this->assertEquals($response->pro_thumbnail, $product->pro_thumbnail);
        $this->assertEquals($response->pro_secure_thumbnail, $product->pro_secure_thumbnail);
        $this->assertEquals($response->pro_accepts_merc_pago, $product->pro_accepts_merc_pago);
        $this->assertEquals($response->pro_load_date, $product->pro_load_date);
        $this->assertEquals($response->pro_sold_quantity, $product->pro_sold_quantity);
        $this->assertEquals($response->pro_inventory, $product->pro_inventory);
    }

    /** @test  */
    public function should_find_a_item_by_sku()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();

        $response = $this->service->findBySku($product->pro_sku);

        $this->assertInstanceOf(Product::class, $response);
        $this->assertEquals($response->pro_id, $product->pro_id);
        $this->assertEquals($response->cat_id, $product->cat_id);
        $this->assertEquals($response->pro_price, $product->pro_price);
        $this->assertEquals($response->pro_oldprice, $product->pro_oldprice);
        $this->assertEquals($response->pro_title, $product->pro_title);
        $this->assertEquals($response->pro_description, $product->pro_description);
        $this->assertEquals($response->pro_description_long, $product->pro_description_long);
        $this->assertEquals($response->pro_image, $product->pro_image);
        $this->assertEquals($response->pro_sku, $product->pro_sku);
        $this->assertEquals($response->pro_seller_id, $product->pro_seller_id);
        $this->assertEquals($response->pro_category_id, $product->pro_category_id);
        $this->assertEquals($response->pro_condition, $product->pro_condition);
        $this->assertEquals($response->pro_permalink, $product->pro_permalink);
        $this->assertEquals($response->pro_thumbnail, $product->pro_thumbnail);
        $this->assertEquals($response->pro_secure_thumbnail, $product->pro_secure_thumbnail);
        $this->assertEquals($response->pro_accepts_merc_pago, $product->pro_accepts_merc_pago);
        $this->assertEquals($response->pro_load_date, $product->pro_load_date);
        $this->assertEquals($response->pro_sold_quantity, $product->pro_sold_quantity);
        $this->assertEquals($response->pro_inventory, $product->pro_inventory);
    }

    /** @test  */
    public function should_exists_item()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();

        $response = $this->service->exists($product->pro_sku);

        $this->assertTrue($response);
    }

    /** @test  */
    public function should_store_item()
    {
        $category = Category::factory()->create();

        $request = Request::create('/', 'POST', [
            'cat_id' => $category->cat_id,
            'pro_title' => $this->faker->title,
            'pro_description' => $this->faker->title,
            'pro_price' => $this->faker->randomFloat(2, 1, 100),
            'pro_oldprice' => $this->faker->randomFloat(2, 1, 100),
            // 'file' => UploadedFile::fake()->image('fake.png'),
        ]);

        $this->productExclusiveServiceMock->shouldReceive('store')
            ->once()
            ->andReturn(true);
        $this->productRelatedServiceMock->shouldReceive('store')
            ->once()
            ->andReturn(true);
        $this->productSpecificationServiceMock->shouldReceive('store')
            ->once()
            ->andReturn(true);

        $response = $this->service->store($request);

        $this->assertInstanceOf(Product::class, $response);
        $this->assertEquals($request->input('pro_title'), $response->pro_title);
        $this->assertEquals($request->input('pro_description'), $response->pro_description);
        $this->assertEquals($request->input('pro_price'), $response->pro_price);
        $this->assertEquals($request->input('pro_oldprice'), $response->pro_oldprice);
    }

    /** @test  */
    public function should_destroy_item()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();

        $request = Request::create('/', 'POST', [
            'id' => $product->pro_id,
        ]);

        $response = $this->service->destroy($request);

        $this->assertTrue($response);
    }
}
