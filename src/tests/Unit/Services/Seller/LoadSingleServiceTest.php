<?php

namespace Tests\Unit\Services\Seller;

use App\Api\MercadoLibre;
use App\Models\Category;
use App\Models\Product;
use App\Services\Seller\LoadCategoryService;
use App\Services\Seller\LoadDescriptionService;
use App\Services\Seller\LoadPictureService;
use App\Services\Seller\LoadProductService;
use App\Services\Seller\LoadSingleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class LoadSingleServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $loadProductServiceMock;
    private $loadPictureServiceMock;
    private $loadDescriptionServiceMock;
    private $loadCategoryServiceMock;
    private $apiMercadoLibreMock;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadProductServiceMock = $this->mock(LoadProductService::class)
            ->makePartial();
        $this->loadPictureServiceMock = $this->mock(LoadPictureService::class)
            ->makePartial();
        $this->loadDescriptionServiceMock = $this
            ->mock(LoadDescriptionService::class)
            ->makePartial();
        $this->loadCategoryServiceMock = $this
            ->mock(LoadCategoryService::class)
            ->makePartial();
        $this->apiMercadoLibreMock = $this->mock(MercadoLibre::class)
            ->makePartial();

        $this->service = new LoadSingleService(
            $this->loadProductServiceMock,
            $this->loadPictureServiceMock,
            $this->loadDescriptionServiceMock,
            $this->loadCategoryServiceMock,
            $this->apiMercadoLibreMock
        );
    }

    /** @test  */
    public function should_load_item()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();
        $productLoadMock = $this->mockLoadProduct($product);

        $this->apiMercadoLibreMock->shouldReceive('getSingleProduct')
            ->once()
            ->andReturn($productLoadMock);

        $this->loadProductServiceMock->shouldReceive('store')
            ->once()
            ->andReturn($product);

        $this->loadPictureServiceMock->shouldReceive('store')
            ->once();

            $this->loadCategoryServiceMock->shouldReceive('store')
            ->once();

        $this->loadDescriptionServiceMock->shouldReceive('loadDescription')
            ->once();

        $this->service->loadProduct($product->sku);
    }

    /** @test  */
    public function should_store_item()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();
        $productLoadMock = $this->mockLoadProduct($product);

        $this->loadProductServiceMock->shouldReceive('store')
            ->once()
            ->andReturn($product);

        $response = $this->service->store($productLoadMock);

        $this->assertInstanceOf(Product::class, $response);
        $this->assertEquals($productLoadMock->title, $response->pro_title);
        $this->assertEquals($productLoadMock->price, $response->pro_price);
        $this->assertEquals($productLoadMock->id, $response->pro_sku);
        $this->assertEquals($productLoadMock->category_id, $response->pro_category_id);
        $this->assertEquals($productLoadMock->condition, $response->pro_condition);
        $this->assertEquals($productLoadMock->permalink, $response->pro_permalink);
        $this->assertEquals($productLoadMock->accepts_mercadopago, $response->pro_accepts_merc_pago);
        $this->assertEquals($productLoadMock->sold_quantity, $response->pro_sold_quantity);
        $this->assertEquals($productLoadMock->loadDate, $response->pro_load_date);
        $this->assertEquals($productLoadMock->seller_id, $response->pro_seller_id);
        $this->assertEquals(true, $response->pro_external);
    }

    private function mockLoadProduct(Product $product)
    {
        $data = [
            'title' => $product->pro_title,
            'price' => $product->pro_price,
            'id' => $product->pro_sku,
            'category_id' => $product->pro_category_id,
            'condition' => $product->pro_condition,
            'permalink' => $product->pro_permalink,
            'accepts_mercadopago' => $product->pro_accepts_merc_pago,
            'sold_quantity' => $product->pro_sold_quantity,
            'loadDate' => $product->pro_load_date,
            'seller_id' => $product->pro_seller_id,
            'secure_thumbnail' => $product->secure_thumbnail,
            'pictures' => [],
        ];
        return json_decode(json_encode($data));
    }
}
