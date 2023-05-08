<?php

namespace Tests\Unit\Services;

use App\Api\MercadoLibre;
use App\Models\Category;
use App\Models\Product;
use App\Services\LoadDescriptionService;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class LoadDescriptionServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $apiMercadoLibreMock;
    private $productServiceMock;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->apiMercadoLibreMock = $this->mock(MercadoLibre::class)->makePartial();
        $this->productServiceMock = $this->mock(ProductService::class)
            ->makePartial();

        $this->service = new LoadDescriptionService(
            $this->apiMercadoLibreMock,
            $this->productServiceMock
        );
    }

    /** @test  */
    public function should_save_items()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();

        $description = ['plain_text' => 'test long description'];
        $this->apiMercadoLibreMock->shouldReceive('getDescriptionProduct')
            ->once()
            ->andReturn(json_decode(json_encode($description)));

        $this->productServiceMock->shouldReceive('findBySku')
            ->once()
            ->andReturn($product);

        $this->service->loadDescription($product->pro_sku);

        $this->assertEquals(
            $description['plain_text'],
            $product->pro_description_long
        );
    }
}
