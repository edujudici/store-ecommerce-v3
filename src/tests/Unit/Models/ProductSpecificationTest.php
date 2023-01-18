<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSpecification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class ProductSpecificationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp() :void
    {
        parent::setUp();

        $this->category = Category::factory()->create();
        $this->product = Product::factory()
            ->create(['cat_id' => $this->category->cat_id]);
    }

    /** @test */
    public function products_specification_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('products_specification', [
                'prs_id', 'pro_sku', 'prs_key', 'prs_value',
            ]),
            1
        );
    }

    /** @test */
    public function a_products_specification_belongs_to_a_product()
    {
        $specifications = ProductSpecification::factory()
            ->create(['pro_sku' => $this->product->pro_sku]);

        $this->assertInstanceOf(Product::class, $specifications->product);
    }
}
