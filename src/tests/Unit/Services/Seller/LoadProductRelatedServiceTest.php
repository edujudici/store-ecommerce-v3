<?php

namespace Tests\Unit\Services\Seller;

use App\Models\Category;
use App\Models\Product;
use App\Services\Product\ProductRelatedService;
use App\Services\Product\ProductService;
use App\Services\Seller\LoadProductRelatedService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class LoadProductRelatedServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $productServiceMock;
    private $productRelatedServiceMock;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->productServiceMock = $this->mock(ProductService::class)
            ->makePartial();
        $this->productRelatedServiceMock = $this->mock(ProductRelatedService::class)
            ->makePartial();

        $this->service = new LoadProductRelatedService(
            $this->productServiceMock,
            $this->productRelatedServiceMock
        );
    }

    /** @test  */
    public function should_load_products_related()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();

        $this->productServiceMock->shouldReceive('findBySku')
            ->once()
            ->andReturn($product);
        $this->productRelatedServiceMock->shouldReceive('store')
            ->once();

        $this->service->loadProductsRelated($product->pro_sku, $this->mockItemsRelated());
    }

    private function mockItemsRelated()
    {
        return json_decode('[{"id":"MLB4427756810","variation_id":181665064395,"stock_relation":1}]');
    }
}
