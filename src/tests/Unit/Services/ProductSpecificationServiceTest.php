<?php

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Models\ProductSpecification;
use App\Services\ProductSpecificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class ProductSpecificationServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new ProductSpecificationService(new Product());
    }

    /** @test  */
    public function should_list_items()
    {
        $product = Product::factory()->create();
        ProductSpecification::factory()->count(3)
            ->create(['pro_sku' => $product->pro_sku]);

        $request = Request::create('/', 'POST', [
            'sku' => $product->pro_sku,
        ]);

        $response = $this->service->index($request);

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_store_item()
    {
        $product = Product::factory()->create();

        $request = Request::create('/', 'POST', [
            'specifications' => [
                [
                    'pro_sku' => $product->pro_sku,
                    'prs_key' => 'Peso',
                    'prs_value' => '50kg',
                ],
                [
                    'pro_sku' => $product->pro_sku,
                    'prs_key' => 'Peso',
                    'prs_value' => '100kg',
                ],
            ],
        ]);

        $this->service->store($product, $request);

        $this->assertCount(2, $product->specifications);
        $this->assertInstanceOf(
            Collection::class,
            $product->specifications
        );
    }
}
