<?php

namespace Tests\Unit\Api;

use App\Api\ApiMercadoLibre;
use App\Models\Category;
use App\Models\MercadoLivre;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
// class MercadoLibreTest extends TestCase
// {
//     use RefreshDatabase, WithFaker;

//     private $apiMercadoLibreMock;

//     public function setUp(): void
//     {
//         parent::setUp();

//         $this->apiMercadoLibreMock = $this->mock(MercadoLibre::class)
//             ->shouldAllowMockingProtectedMethods()
//             ->makePartial();
//         $this->apiMercadoLibreMock->__construct(new MercadoLivre());
//     }

//     /** @test  */
//     public function should_get_single_product()
//     {
//         $product = Product::factory()
//             ->for(Category::factory())
//             ->create();
//         $mockResponse = $this->mockLoadProduct($product);

//         $this->apiMercadoLibreMock->shouldReceive('runCurl')
//                 ->once()
//                 ->andReturn(json_encode($mockResponse));

//         $response = $this->apiMercadoLibreMock->getSingleProduct($product->pro_sku);

//         $this->assertIsObject($response);
//         $this->assertEquals($mockResponse['title'], $response->title);
//         $this->assertEquals($mockResponse['price'], $response->price);
//         $this->assertEquals($mockResponse['id'], $response->id);
//         $this->assertEquals($mockResponse['category_id'], $response->category_id);
//         $this->assertEquals($mockResponse['condition'], $response->condition);
//         $this->assertEquals($mockResponse['permalink'], $response->permalink);
//         $this->assertEquals($mockResponse['accepts_mercadopago'], $response->accepts_mercadopago);
//         $this->assertEquals($mockResponse['sold_quantity'], $response->sold_quantity);
//         $this->assertEquals($mockResponse['loadDate'], $response->loadDate);
//         $this->assertEquals($mockResponse['seller_id'], $response->seller_id);
//         $this->assertEquals(json_decode(json_encode($mockResponse['pictures'])), $response->pictures);
//     }

//     /** @test  */
//     public function should_get_products_pictures()
//     {
//         $product = Product::factory()
//             ->for(Category::factory())
//             ->create();
//         $mockResponse = $this->mockLoadProduct($product);

//         $this->apiMercadoLibreMock->shouldReceive('runCurl')
//                 ->once()
//                 ->andReturn(json_encode($mockResponse));

//         $response = $this->apiMercadoLibreMock->getProductsPictures([$product->pro_sku]);

//         $this->assertIsObject($response);
//         $this->assertEquals($mockResponse['id'], $response->id);
//         $this->assertEquals(json_decode(json_encode($mockResponse['pictures'])), $response->pictures);
//         $this->assertCount(1, $response->pictures);
//     }

//     /** @test  */
//     public function should_get_multiple_products()
//     {
//         $mercadoLivre = MercadoLivre::factory()->create();

//         $mockResponse = $this->mockLoadProducts();

//         $this->apiMercadoLibreMock->shouldReceive('runCurl')
//                 ->once()
//                 ->andReturn(json_encode($mockResponse));

//         $response = $this->apiMercadoLibreMock->getMultipleProducts(
//             $mercadoLivre->mel_id
//         );

//         $this->assertIsObject($response);
//         // $this->assertObjectHasProperty('results', $response);
//         $this->assertCount(2, $response->results);
//     }

//     /** @test  */
//     public function should_get_multiple_products_expired_token()
//     {
//         $mercadoLivre = MercadoLivre::factory()->create();

//         $mockResponseExpiredToken = json_encode($this->mockLoadProductsExpiredToken());
//         $mockRefreshToken = json_encode($this->mockRefreshToken());
//         $mockResponse = json_encode($this->mockLoadProducts());

//         $this->apiMercadoLibreMock->shouldReceive('runCurl')
//                 ->times(3)
//                 ->andReturn($mockResponseExpiredToken, $mockRefreshToken, $mockResponse);

//         $response = $this->apiMercadoLibreMock->getMultipleProducts(
//             $mercadoLivre->mel_id
//         );

//         $this->assertIsObject($response);
//         // $this->assertObjectHasAttribute('results', $response);
//         $this->assertCount(2, $response->results);
//     }

//     /** @test  */
//     public function should_get_detail_category()
//     {
//         $product = Product::factory()
//             ->for(Category::factory())
//             ->create();
//         MercadoLivre::factory()->create();
//         $mockResponse = $this->mockLoadCategory();

//         $this->apiMercadoLibreMock->shouldReceive('runCurl')
//                 ->once()
//                 ->andReturn(json_encode($mockResponse));

//         $response = $this->apiMercadoLibreMock->getDetailCategory($product->pro_category_id);

//         $this->assertIsObject($response);
//         $this->assertEquals($mockResponse['id'], $response->id);
//         $this->assertEquals($mockResponse['name'], $response->name);
//     }

//     /** @test  */
//     public function should_get_description_product()
//     {
//         $product = Product::factory()
//             ->for(Category::factory())
//             ->create();
//         MercadoLivre::factory()->create();
//         $mockResponse = $this->mockLoadDescription();

//         $this->apiMercadoLibreMock->shouldReceive('runCurl')
//                 ->once()
//                 ->andReturn(json_encode($mockResponse));

//         $response = $this->apiMercadoLibreMock->getDescriptionProduct($product->pro_sku);

//         $this->assertIsObject($response);
//         $this->assertEquals($mockResponse['plain_text'], $response->plain_text);
//     }

//     private function mockLoadProduct(Product $product): array
//     {
//         return [
//             'title' => $product->pro_title,
//             'price' => $product->pro_price,
//             'id' => $product->pro_sku,
//             'category_id' => $product->pro_category_id,
//             'condition' => $product->pro_condition,
//             'permalink' => $product->pro_permalink,
//             'accepts_mercadopago' => $product->pro_accepts_merc_pago,
//             'sold_quantity' => $product->pro_sold_quantity,
//             'loadDate' => $product->pro_load_date,
//             'seller_id' => $product->pro_seller_id,
//             'pictures' => [
//                 [
//                     'pro_sku' => $product->pro_sku,
//                     'pic_id_secondary' => $this->faker->randomNumber(9),
//                     'pic_image' => $this->faker->url,
//                     'pic_url' => $this->faker->url,
//                     'pic_secure_url' => $this->faker->url,
//                     'pic_size' => '500x476',
//                     'pic_max_size' => '750x715',
//                     'pic_quality' => '',
//                 ]
//             ],
//         ];
//     }

//     private function mockLoadProducts(): array
//     {
//         $products = Product::factory()
//             ->count(2)
//             ->for(Category::factory())
//             ->create();

//         $list = [
//             'results' => [],
//         ];

//         $list['results'][] = $this->mockLoadProduct($products[0]);
//         $list['results'][] = $this->mockLoadProduct($products[1]);

//         return $list;
//     }

//     private function mockLoadProductsExpiredToken(): array
//     {
//         return [
//             'status' => 401,
//         ];
//     }

//     private function mockRefreshToken(): array
//     {
//         return [
//             'access_token' => $this->faker->word,
//             'refresh_token' => $this->faker->word,
//             'token_type' => 'bearer',
//             'expires_in' => $this->faker->randomNumber(9),
//             'scope' => $this->faker->url,
//             'user_id' => $this->faker->randomNumber(9),
//         ];
//     }

//     private function mockLoadCategory(): array
//     {
//         return [
//             'id' => $this->faker->randomNumber(2),
//             'name' => $this->faker->title,
//         ];
//     }

//     private function mockLoadDescription(): array
//     {
//         return [
//             'plain_text' => $this->faker->title,
//         ];
//     }
// }
