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

    private $product;

    public function setUp() :void
    {
        parent::setUp();

        $this->product = Product::factory()
            ->for(Category::factory())
            ->create();

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
        $productExclusive = ProductExclusive::factory()
            ->for($this->product)
            ->create();

        $this->assertInstanceOf(Product::class, $productExclusive->product);
    }
}
