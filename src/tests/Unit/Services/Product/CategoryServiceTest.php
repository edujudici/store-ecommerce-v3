<?php

namespace Tests\Unit\Services\Product;

use App\Models\Category;
use App\Services\Product\CategoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class CategoryServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new CategoryService(new Category());
    }

    /** @test  */
    public function should_list_items()
    {
        Category::factory()->count(3)->create();

        $response = $this->service->index();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_find_a_item()
    {
        $category = Category::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $category->cat_id,
        ]);

        $response = $this->service->findById($request);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertEquals($response->cat_title, $category->cat_title);
        $this->assertEquals($response->cat_image, $category->cat_image);
        $this->assertEquals(
            $response->cat_id_secondary,
            $category->cat_id_secondary
        );
    }

    /** @test  */
    public function should_store_item()
    {
        $request = Request::create('/', 'POST', [
            'cat_title' => $this->faker->word,
            // 'file' => UploadedFile::fake()->image('fake.png'),
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertEquals($request->input('cat_title'), $response->cat_title);
    }

    /** @test  */
    public function should_update_item()
    {
        $category = Category::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $category->cat_id,
            'cat_title' => $this->faker->word,
            // 'file' => UploadedFile::fake()->image('fake.png'),
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertEquals($request->input('id'), $response->cat_id);
        $this->assertEquals($request->input('cat_title'), $response->cat_title);
    }

    /** @test  */
    public function should_destroy_item()
    {
        $category = Category::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $category->cat_id,
        ]);

        $response = $this->service->destroy($request);

        $this->assertTrue($response);
    }
}
