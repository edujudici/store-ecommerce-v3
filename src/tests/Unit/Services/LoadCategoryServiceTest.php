<?php

namespace Tests\Unit\Services;

use App\Api\MercadoLibre;
use App\Models\Category;
use App\Models\Product;
use App\Services\LoadCategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class LoadCategoryServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

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
        Product::factory()->create();

        $this->apiMercadoLibreMock->shouldReceive('getDetailCategory')
            ->once()
            ->andReturn(json_decode('{"id": 1, "name": "test"}'));

        $this->service->organizeCategories();

        $this->assertCount(1, Category::all());
        $this->assertEquals('test', Category::first()->cat_title);
    }
}
