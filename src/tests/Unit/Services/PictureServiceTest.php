<?php

namespace Tests\Unit\Services;

use App\Models\Category;
use App\Models\Picture;
use App\Models\Product;
use App\Services\PictureService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class PictureServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new PictureService(new Product());
    }

    /** @test  */
    public function should_list_items()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();
        Picture::factory()->count(3)->create(['pro_sku' => $product->pro_sku]);

        $request = Request::create('/', 'POST', [
            'sku' => $product->pro_sku,
        ]);

        $response = $this->service->index($request);

        $this->assertIsArray($response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_store_item()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();
        $paths = [
            $this->faker->url,
            $this->faker->url,
        ];

        $this->service->store($product, $paths);

        $this->assertEquals($paths[0], $product->pro_image);
        $this->assertCount(2, $product->pictures);
    }
}
