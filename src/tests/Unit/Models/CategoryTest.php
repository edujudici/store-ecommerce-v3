<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;

/**
 * @group ModelTest
 */
class CategoryTest extends TestCase
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
    public function categories_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('categories', [
                'cat_id', 'cat_title', 'cat_image', 'cat_id_secondary',
            ]),
            1
        );
    }

    /** @test */
    public function a_category_has_many_products()
    {
        $this->assertTrue($this->category->products->contains($this->product));
        $this->assertCount(1, $this->category->products);
        $this->assertInstanceOf(Collection::class, $this->category->products);
    }

    /** @test */
    public function a_category_has_many_products_mercado_livre()
    {
        $this->product = Product::factory()
            ->create(['pro_category_id' => $this->category->cat_id_secondary]);

        $this->assertTrue($this->category->productsML->contains($this->product));
        $this->assertCount(1, $this->category->productsML);
        $this->assertInstanceOf(Collection::class, $this->category->productsML);
    }
}
