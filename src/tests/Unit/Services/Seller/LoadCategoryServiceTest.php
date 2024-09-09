<?php

namespace Tests\Unit\Services\Seller;

use App\Api\ApiMercadoLibre;
use App\Models\Category;
use App\Models\Product;
use App\Services\Seller\LoadCategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class LoadCategoryServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $apiMercadoLibreMock;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->apiMercadoLibreMock = $this->mock(MercadoLibre::class)
            ->makePartial();

        $this->service = new LoadCategoryService(
            new Product(),
            new Category(),
            $this->apiMercadoLibreMock
        );
    }

    /** @test  */
    public function should_save_items()
    {
        $category = Category::factory()
            ->create([
                'cat_id_secondary' => 123,
                'cat_seller_id' => 456,
                'cat_visible' => true
            ]);
        $product = Product::factory()
            ->for($category)
            ->create([
                'pro_seller_id' => $category->cat_seller_id,
            ]);

        $data = [
            'id' => $category->cat_id_secondary,
            'name' => $category->cat_title
        ];

        $this->apiMercadoLibreMock->shouldReceive('getDetailCategory')
            ->once()
            ->andReturn(json_decode(json_encode($data)));

        $this->service->organizeCategories($product->pro_seller_id);

        $this->assertCount(1, Category::all());
        $this->assertEquals($category->cat_title, Category::first()->cat_title);
        $this->assertEquals($product->pro_seller_id, Category::first()->cat_seller_id);
    }
}
