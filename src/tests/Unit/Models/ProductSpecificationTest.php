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

    private $product;

    public function setUp() :void
    {
        parent::setUp();

        $this->product = Product::factory()
            ->for(Category::factory())
            ->create();
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
            ->for($this->product)
            ->create();

        $this->assertInstanceOf(Product::class, $specifications->product);
    }
}
