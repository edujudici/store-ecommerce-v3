<?php

namespace Tests\Unit\Services\Product;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductExclusive;
use App\Services\Product\ProductExclusiveService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class ProductExclusiveServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new ProductExclusiveService(new ProductExclusive());
    }

    /** @test  */
    public function should_list_items()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();
        ProductExclusive::factory()->count(3)
            ->create(['pro_sku' => $product->pro_sku]);

        $request = Request::create('/', 'POST', []);

        $response = $this->service->index($request);

        $this->assertIsArray($response);
        $this->assertCount(1, $response);
    }

    /** @test  */
    public function should_store_item()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();
        $request = Request::create('/', 'POST', [
            'isProductExclusive' => true,
        ]);

        $this->service->store($product, $request);

        $this->assertEquals(1, $product->exclusiveDeal->count());
        $this->assertInstanceOf(
            ProductExclusive::class,
            $product->exclusiveDeal
        );
    }

    /** @test  */
    public function should_destroy_item()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();
        $request = Request::create('/', 'POST', [
        ]);

        $this->service->store($product, $request);
        $this->assertNull($product->exclusiveDeal);
    }
}
