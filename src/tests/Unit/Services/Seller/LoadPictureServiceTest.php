<?php

namespace Tests\Unit\Services\Seller;

use App\Models\Category;
use App\Models\Product;
use App\Services\Seller\LoadPictureService;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class LoadPictureServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $productServiceMock;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->productServiceMock = $this->mock(ProductService::class)
            ->makePartial();

        $this->service = new LoadPictureService(
            $this->productServiceMock
        );
    }

    /** @test  */
    public function should_load_pictures()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();
        $pictures = $this->mockPictures();

        $this->productServiceMock->shouldReceive('findBySku')
            ->once()
            ->andReturn($product);

        $this->service->loadPictures($product->pro_sku, $pictures);

        $this->assertCount(2, $product->pictures);
        $this->assertInstanceOf(Collection::class, $product->pictures);
    }

    public function test_save()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();
        $pictures = $this->mockPictures();

        $this->service->store($product, $pictures);

        $this->assertCount(2, $product->pictures);
        $this->assertInstanceOf(Collection::class, $product->pictures);
        $this->assertEquals($product->pro_thumbnail, $pictures[0]->url);
        $this->assertEquals($product->pro_secure_thumbnail, $pictures[0]->secure_url);
    }

    private function mockPictures()
    {
        $data = [
            [
                'id' => 1,
                'url' => $this->faker->url,
                'secure_url' => $this->faker->url,
                'size' => $this->faker->randomNumber(2),
                'max_size' => $this->faker->randomNumber(2),
                'quality' => $this->faker->randomNumber(2),
            ],
            [
                'id' => 1,
                'url' => $this->faker->url,
                'secure_url' => $this->faker->url,
                'size' => $this->faker->randomNumber(2),
                'max_size' => $this->faker->randomNumber(2),
                'quality' => $this->faker->randomNumber(2),
            ]
        ];

        return json_decode(json_encode($data));
    }
}
