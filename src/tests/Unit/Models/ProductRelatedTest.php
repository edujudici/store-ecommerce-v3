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

    public function setUp() :void
    {
        parent::setUp();

        $this->category = Category::factory()->create();
        $this->product = Product::factory()
            ->create(['cat_id' => $this->category->cat_id]);
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
            ->create(['pro_sku' => $this->product->pro_sku]);

        $this->assertInstanceOf(Product::class, $productRelated->product);
    }
}
