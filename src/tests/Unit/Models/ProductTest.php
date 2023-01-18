<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\ProductExclusive;
use App\Models\Picture;
use App\Models\Product;
use App\Models\ProductComment;
use App\Models\ProductRelated;
use App\Models\ProductSpecification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->category = Category::factory()->create();
        $this->product = Product::factory()
            ->create(['pro_category_id' => $this->category->cat_id_secondary]);
        $this->product2 = Product::factory()
            ->create(['cat_id' => $this->category->cat_id]);
    }

    /** @test */
    public function products_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('products', [
                'pro_id', 'cat_id', 'pro_price', 'pro_oldprice', 'pro_title',
                'pro_description', 'pro_description_long', 'pro_image',
                'pro_sku', 'pro_seller_id', 'pro_category_id', 'pro_condition',
                'pro_permalink', 'pro_thumbnail', 'pro_secure_thumbnail',
                'pro_accepts_merc_pago', 'pro_load_date', 'pro_sold_quantity',
                'pro_external', 'pro_inventory',
            ]),
            1
        );
    }

    /** @test */
    public function a_product_belongs_to_a_category_ml()
    {
        $this->assertInstanceOf(Category::class, $this->product->categoryML);
    }

    /** @test */
    public function a_product_belongs_to_a_category()
    {
        $this->assertInstanceOf(Category::class, $this->product2->category);
    }

    /** @test */
    public function a_product_item_has_a_exclusive_deal()
    {
        ProductExclusive::factory()
            ->create(['pro_sku' => $this->product->pro_sku]);

        $this->assertInstanceOf(
            ProductExclusive::class,
            $this->product->exclusiveDeal
        );
        $this->assertEquals(1, $this->product->exclusiveDeal->count());
    }

    /** @test */
    public function a_product_has_many_products_related()
    {
        $productRelated = ProductRelated::factory()
            ->create(['pro_sku' => $this->product->pro_sku]);

        $this->assertTrue($this->product->productsRelated
            ->contains($productRelated));
        $this->assertCount(1, $this->product->productsRelated);
        $this->assertInstanceOf(
            Collection::class,
            $this->product->productsRelated
        );
    }

    /** @test */
    public function a_product_has_many_specifications()
    {
        $specification = ProductSpecification::factory()
            ->create(['pro_sku' => $this->product->pro_sku]);

        $this->assertTrue($this->product->specifications
            ->contains($specification));
        $this->assertCount(1, $this->product->specifications);
        $this->assertInstanceOf(
            Collection::class,
            $this->product->specifications
        );
    }

    /** @test */
    public function a_product_has_many_pictures()
    {
        $picture = Picture::factory()
            ->create(['pro_sku' => $this->product->pro_sku]);

        $this->assertTrue($this->product->pictures
            ->contains($picture));
        $this->assertCount(1, $this->product->pictures);
        $this->assertInstanceOf(
            Collection::class,
            $this->product->pictures
        );
    }

    /** @test */
    public function a_product_has_many_comments()
    {
        $productComent = ProductComment::factory()
            ->create(['pro_sku' => $this->product->pro_sku]);

        $this->assertTrue($this->product->comments
            ->contains($productComent));
        $this->assertCount(1, $this->product->comments);
        $this->assertInstanceOf(
            Collection::class,
            $this->product->comments
        );
    }

    /** @test */
    public function search_items()
    {
        $request = Request::create('/', 'POST', [
            'search' => 'test',
            'category' => 1,
            'order' => 'sold',
            'price' => 50,
        ]);

        $query = $this->product->search($request);
        $this->assertInstanceOf(Builder::class, $query);
    }

    /** @test */
    public function search_items_not_sold()
    {
        $request = Request::create('/', 'POST', [
            'order' => 'asc'
        ]);

        $query = $this->product->search($request);
        $this->assertInstanceOf(Builder::class, $query);
    }

    /** @test */
    public function search_items_all_prices()
    {
        $request = Request::create('/', 'POST', [
            'price' => '-1'
        ]);

        $query = $this->product->search($request);
        $this->assertInstanceOf(Builder::class, $query);
    }
}
