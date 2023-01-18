<?php

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Models\ProductComment;
use App\Services\ProductCommentService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class ProductCommentServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new ProductCommentService(
            new Product(),
            new ProductComment()
        );
    }

    /** @test  */
    public function should_list_all_items()
    {
        $product = Product::factory()->create();
        ProductComment::factory()->count(3)
            ->create(['pro_sku' => $product->pro_sku]);

        $response = $this->service->indexAll();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_list_items()
    {
        $product = Product::factory()->create();
        ProductComment::factory()->count(3)
            ->create(['pro_sku' => $product->pro_sku]);

        $request = Request::create('/', 'POST', [
            'sku' => $product->pro_sku,
        ]);

        $response = $this->service->index($request);

        $this->assertIsArray($response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_find_a_item()
    {
        $comment = ProductComment::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $comment->prc_id,
        ]);

        $response = $this->service->findById($request);

        $this->assertInstanceOf(ProductComment::class, $response);
        $this->assertEquals($response->prc_id, $comment->prc_id);
        $this->assertEquals($response->pro_sku, $comment->pro_sku);
        $this->assertEquals($response->prc_name, $comment->prc_name);
        $this->assertEquals($response->prc_question, $comment->prc_question);
        $this->assertEquals($response->prc_answer, $comment->prc_answer);
        $this->assertEquals($response->prc_answer_date, $comment->prc_answer_date);
    }

    /** @test  */
    public function should_store_item()
    {
        $product = Product::factory()->create();

        $request = Request::create('/', 'POST', [
            'sku' => $product->pro_sku,
            'prc_name' => $this->faker->firstName,
            'prc_question' => $this->faker->title,
            'prc_answer' => $this->faker->title,
            'prc_answer_date' => $this->faker->date(),
        ]);

        $response = $this->service->store($request);

        $this->assertIsArray($response);
        $this->assertCount(1, $response);
    }

    /** @test  */
    public function should_destroy_item()
    {
        $productComment = ProductComment::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $productComment->prc_id,
        ]);

        $response = $this->service->destroy($request);

        $this->assertTrue($response);
    }
}
