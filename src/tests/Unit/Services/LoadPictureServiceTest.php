<?php

namespace Tests\Unit\Services;

use App\Api\MercadoLibre;
use App\Models\Product;
use App\Services\LoadPictureService;
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

    public function setUp(): void
    {
        parent::setUp();

        $this->apiMercadoLibreMock = $this->mock(MercadoLibre::class)
            ->makePartial();
        $this->productServiceMock = $this->mock(ProductService::class)
            ->makePartial();

        $this->service = new LoadPictureService(
            $this->apiMercadoLibreMock,
            $this->productServiceMock
        );
    }

    /** @test  */
    public function should_load_pictures()
    {
        $pictures = $this->mockResponse();

        $product = Product::factory()->create();

        $this->apiMercadoLibreMock->shouldReceive('getProductsPictures')
            ->once()
            ->andReturn($pictures);

        $this->productServiceMock->shouldReceive('findBySku')
            ->once()
            ->andReturn($product);

        $this->service->loadPictures([$product->pro_sku]);

        $this->assertCount(2, $product->pictures);
        $this->assertInstanceOf(Collection::class, $product->pictures);
    }

    public function test_save()
    {
        $product = Product::factory()->create();
        $pictures = $this->mockPictures();

        $this->service->store($product, $pictures);

        $this->assertCount(2, $product->pictures);
        $this->assertInstanceOf(Collection::class, $product->pictures);
        $this->assertEquals($product->pro_thumbnail, $pictures[0]->url);
        $this->assertEquals($product->pro_secure_thumbnail, $pictures[0]->secure_url);
    }

    private function mockResponse()
    {
        $data = [
            [
                'body' => [
                    'id' => $this->faker->randomNumber(2),
                    'pictures' => $this->mockPictures()
                ],
            ],
        ];

        return json_decode(json_encode($data));
    }

    private function mockPictures()
    {
        $data = [
            [
                'id' => '',
                'url' => '',
                'secure_url' => '',
                'size' => '',
                'max_size' => '',
                'quality' => '',
            ],
            [
                'id' => '',
                'url' => '',
                'secure_url' => '',
                'size' => '',
                'max_size' => '',
                'quality' => '',
            ]
        ];

        return json_decode(json_encode($data));
    }
}
