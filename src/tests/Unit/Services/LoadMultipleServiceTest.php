<?php

namespace Tests\Unit\Services;

use App\Api\MercadoLibre;
use App\Models\LoadHistory;
use App\Models\MercadoLivre;
use App\Models\Product;
use App\Services\LoadCategoryService;
use App\Services\LoadDescriptionService;
use App\Services\LoadHistoryService;
use App\Services\LoadMultipleService;
use App\Services\LoadPictureService;
use App\Services\LoadProductService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class LoadMultipleServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadProductServiceMock = $this->mock(LoadProductService::class)
            ->makePartial();
        $this->loadHistoryServiceMock = $this->mock(LoadHistoryService::class)
            ->makePartial();
        $this->apiMercadoLibreMock = $this->mock(MercadoLibre::class)
            ->makePartial();

        $this->service = new LoadMultipleService(
            $this->loadProductServiceMock,
            $this->loadHistoryServiceMock,
            $this->apiMercadoLibreMock
        );
    }

    /** @test  */
    public function should_dispatch_products()
    {
        $mercadoLivre = MercadoLivre::factory()->create();

        $request = Request::create('/', 'POST', [
            'mlAccountId' => $mercadoLivre->mel_id,
            'mlAccountTitle' => $mercadoLivre->mel_title
        ]);

        $products = $this->mockMultipleProductsPaginate();

        $this->loadProductServiceMock->shouldReceive('destroy')
            ->once()
            ->andReturn(true);
        $this->loadHistoryServiceMock->shouldReceive('store')
            ->once()
            ->andReturn(new LoadHistory());
        $this->apiMercadoLibreMock->shouldReceive('getMultipleProducts')
            ->once()
            ->andReturn($products);

        $this->mock(LoadMultipleService::class)
            ->makePartial()
            ->shouldReceive('loadProducts')
            ->twice();
        $this->mock(LoadCategoryService::class)
            ->makePartial()
            ->shouldReceive('organizeCategories')
            ->once();

        $this->service->dispatchProducts($request);
    }

    /** @test  */
    public function should_load_products_error()
    {
        $this->expectException(Exception::class);

        $mercadoLivre = MercadoLivre::factory()->create();
        $loadDate = date('Y-m-d H:i:s');
        $offset = $this->faker->randomNumber(2);

        $this->apiMercadoLibreMock->shouldReceive('getMultipleProducts')
            ->once()
            ->andReturn(null);

        $this->service->loadProducts(
            $offset,
            $loadDate,
            $mercadoLivre->mel_id
        );
    }

    /** @test  */
    public function should_load_products()
    {
        $mercadoLivre = MercadoLivre::factory()->create();

        $loadDate = date('Y-m-d H:i:s');
        $offset = $this->faker->randomNumber(2);

        $product = Product::factory()->create();
        $products = $this->mockMultipleProducts($product);

        $this->apiMercadoLibreMock->shouldReceive('getMultipleProducts')
            ->once()
            ->andReturn($products);

        $this->loadProductServiceMock->shouldReceive('storeProducts')
            ->once();

        $this->mock(LoadDescriptionService::class)
            ->makePartial()
            ->shouldReceive('loadDescriptions')
            ->once();
        $this->mock(LoadPictureService::class)
            ->makePartial()
            ->shouldReceive('loadPictures')
            ->once();

        $this->service->loadProducts(
            $offset,
            $loadDate,
            $mercadoLivre->mel_id
        );
    }

    private function mockMultipleProducts(Product $product)
    {
        $data = [
            'results' => [
                [
                    'title' => $product->pro_title,
                    'price' => $product->pro_price,
                    'id' => $product->pro_sku,
                    'category_id' => $product->pro_category_id,
                    'condition' => $product->pro_condition,
                    'permalink' => $product->pro_permalink,
                    'accepts_mercadopago' => $product->pro_accepts_merc_pago,
                    'sold_quantity' => $product->pro_sold_quantity,
                    'seller_id' => $product->pro_seller_id,
                    'secure_thumbnail' => $product->secure_thumbnail,
                ]
            ]
        ];

        return json_decode(json_encode($data));
    }

    private function mockMultipleProductsPaginate()
    {
        $data = [
            'paging' => [
                'total' => 100,
            ],
            'results' => []
        ];

        $resultData = [
            'seller' => [
                'id' => 1
            ]
        ];
        $result = json_decode(json_encode($resultData));
        $data['results'][] = $result;

        return json_decode(json_encode($data));
    }
}
