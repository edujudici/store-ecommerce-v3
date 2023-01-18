<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductComment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class ProductCommentTest extends TestCase
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
    public function products_comment_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('products_comment', [
                'prc_id', 'pro_sku', 'prc_name', 'prc_question', 'prc_answer',
                'prc_answer_date',
            ]),
            1
        );
    }

    /** @test */
    public function a_product_comment_belongs_to_a_product()
    {
        $productComment = ProductComment::factory()
            ->create(['pro_sku' => $this->product->pro_sku]);

        $this->assertInstanceOf(Product::class, $productComment->product);
    }
}
