<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductRelated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class ProductRelatedTest extends TestCase
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
    public function products_related_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('products_related', [
                'prr_id', 'pro_sku', 'pro_sku_related',
            ]),
            1
        );
    }

    /** @test */
    public function a_product_related_belongs_to_a_product()
    {
        $productRelated = ProductRelated::factory()
            ->for($this->product)
            ->create();

        $this->assertInstanceOf(Product::class, $productRelated->product);
    }
}
