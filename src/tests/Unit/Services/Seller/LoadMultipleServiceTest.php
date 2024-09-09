<?php

namespace Tests\Unit\Services\Seller;

use App\Api\ApiMercadoLibre;
use App\Jobs\LoadCategory;
use App\Jobs\LoadProduct;
use App\Jobs\LoadProductDescription;
use App\Jobs\LoadProductPicture;
use App\Jobs\LoadProductRelated;
use App\Models\Category;
use App\Models\MercadoLivre;
use App\Models\Product;
use App\Services\Seller\LoadHistoryService;
use App\Services\Seller\LoadMultipleService;
use App\Services\Seller\LoadProductService;
use App\Services\Seller\MercadoLivreService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class LoadMultipleServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $loadProductServiceMock;
    private $loadHistoryServiceMock;
    private $apiMercadoLibreMock;
    private $service;
    private $mercadoLivreServiceMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadProductServiceMock = $this->mock(LoadProductService::class)
            ->makePartial();
        $this->loadHistoryServiceMock = $this->mock(LoadHistoryService::class)
            ->makePartial();
        $this->apiMercadoLibreMock = $this->mock(MercadoLibre::class)
            ->makePartial();
        $this->mercadoLivreServiceMock = $this->mock(MercadoLivreService::class)
            ->makePartial();

        $this->service = new LoadMultipleService(
            $this->loadProductServiceMock,
            $this->loadHistoryServiceMock,
            $this->apiMercadoLibreMock,
            $this->mercadoLivreServiceMock
        );
    }

    /** @test  */
    public function should_dispatch_products()
    {
        Queue::fake();

        $mercadoLivre = MercadoLivre::factory()->create();

        $request = Request::create('/', 'POST', [
            'mlAccountId' => $mercadoLivre->mel_id,
            'mlAccountTitle' => $mercadoLivre->mel_title
        ]);

        $this->apiMercadoLibreMock->shouldReceive('getMultipleProducts')
            ->once()
            ->andReturn($this->mockMultipleProducts());
        $this->loadProductServiceMock->shouldReceive('destroy')
            ->once();
        $this->loadHistoryServiceMock->shouldReceive('store')
            ->once();
        $this->mercadoLivreServiceMock->shouldReceive('findById')
            ->once()
            ->andReturn(new MercadoLivre());

        $this->service->dispatchProducts($request);

        Queue::assertPushedOn('products', LoadProduct::class);
        Queue::assertPushed(LoadProduct::class, 1);
    }

    /** @test  */
    public function should_load_products()
    {
        Queue::fake();

        $mercadoLivre = MercadoLivre::factory()->create();

        $loadDate = date('Y-m-d H:i:s');
        $offset = $this->faker->randomNumber(2);
        $skus = $this->generateSkusLists();

        $this->mercadoLivreServiceMock->shouldReceive('findById')
            ->twice()
            ->andReturn($mercadoLivre);
        $this->apiMercadoLibreMock->shouldReceive('getMultipleProductsDetails')
            ->twice()
            ->andReturn($this->mockMultipleProductsDetails());
        $this->loadProductServiceMock->shouldReceive('storeProducts')
            ->twice();
        $this->apiMercadoLibreMock->shouldReceive('getMultipleProducts')
            ->once()
            ->andReturn($this->mockMultipleProducts());

        $this->service->loadProducts($loadDate, $mercadoLivre->mel_id, $skus, $offset);

        Queue::assertPushedOn('description', LoadProductDescription::class);
        Queue::assertPushed(LoadProductDescription::class, 2);

        Queue::assertPushedOn('pictures', LoadProductPicture::class);
        Queue::assertPushed(LoadProductPicture::class, 2);

        Queue::assertPushedOn('products', LoadProductRelated::class);
        Queue::assertPushed(LoadProductRelated::class, 2);

        Queue::assertPushedOn('products', LoadCategory::class);
        Queue::assertPushed(LoadCategory::class, 2);
    }

    private function mockMultipleProducts()
    {
        $data = [
            'paging' => [
                'total' => 100,
            ],
            'seller_id' => 1,
            'results' => ["a", 'b']
        ];

        return json_decode(json_encode($data));
    }

    private function mockMultipleProductsDetails()
    {
        $product = Product::factory()
            ->for(Category::factory())
            ->create();
        $data = [];

        $product1 = [
            'body' => [
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
                'item_relations' => [],
            ]
        ];

        $data[] = json_decode(json_encode($product1));

        return $data;
    }

    private function generateSkusLists()
    {
        $skus = [];
        for ($i = 1; $i <= 20; $i++) {
            $skus[] = $this->faker->word;
        }
        return $skus;
    }
}
