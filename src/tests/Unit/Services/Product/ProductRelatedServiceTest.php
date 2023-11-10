<?php

namespace Tests\Unit\Services\Product;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductRelated;
use App\Services\Product\ProductRelatedService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class ProductRelatedServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new ProductRelatedService(new ProductRelated());
    }

    /** @test  */
    public function should_list_items_formatted()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();
        $productsRelated = ProductRelated::factory()->count(3)
            ->create([
                'pro_sku' => $product->pro_sku,
                'pro_sku_related' => $product->pro_sku
            ]);

        $request = Request::create('/', 'POST', []);

        $response = $this->service->indexFormat($request);

        $this->assertArrayHasKey('products', $response);
        $this->assertCount(6, $response['products']);
    }

    /** @test  */
    public function should_list_items()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();
        ProductRelated::factory()->count(3)
            ->create([
                'pro_sku' => $product->pro_sku,
                'pro_sku_related' => $product->pro_sku
            ]);

        $request = Request::create('/', 'POST', [
            'sku' => $product->pro_sku,
        ]);

        $response = $this->service->index($request);

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(6, $response);
    }

    /** @test  */
    public function should_store_item()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();

        $request = Request::create('/', 'POST', [
            'products' => [
                [
                    'pro_sku' => $product->pro_sku,
                    'pro_sku_related' => $product->pro_sku,
                ],
                [
                    'pro_sku' => $product->pro_sku,
                    'pro_sku_related' => $product->pro_sku,
                ],
            ],
        ]);

        $this->service->store($product, $request);

        $this->assertCount(2, $product->productsRelated);
        $this->assertInstanceOf(
            Collection::class,
            $product->productsRelated
        );
    }
}
