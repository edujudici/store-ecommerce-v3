<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\ProductExclusive;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class ProductExclusiveTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp() :void
    {
        parent::setUp();

        $this->category = Category::factory()->create();
        $this->product = Product::factory()
            ->create(['cat_id' => $this->category->cat_id]);
        $this->productExclusive = ProductExclusive::factory()
            ->create(['pro_sku' => $this->product->pro_sku]);
    }

    /** @test */
    public function products_exclusive_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('products_exclusive', [
                'pre_id', 'pro_sku',
            ]),
            1
        );
    }

    /** @test */
    public function a_product_exclusive_belongs_to_a_product()
    {
        $this->assertInstanceOf(Product::class, $this->productExclusive->product);
    }
}
