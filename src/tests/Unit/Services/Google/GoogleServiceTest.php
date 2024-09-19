<?php

namespace Tests\Unit\Services\Google;

use App\Models\Google;
use App\Services\Google\GoogleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
// class GoogleServiceTest extends TestCase
// {
//     use RefreshDatabase, WithFaker;

//     private $service;

//     public function setUp(): void
//     {
//         parent::setUp();

//         $this->service = new GoogleService(
//             new Google()
//         );
//     }

//     /** @test  */
//     public function should_find_a_first_item()
//     {
//         $google = Google::factory()->create();

//         $response = $this->service->firstGoogle();

//         $this->assertInstanceOf(Google::class, $response);
//         $this->assertNotNull($response->goo_id);
//         $this->assertEquals($response->goo_token_type, $google->goo_token_type);
//         $this->assertEquals($response->goo_expires_in, $google->goo_expires_in);
//         $this->assertEquals($response->goo_access_token, $google->goo_access_token);
//         $this->assertEquals($response->goo_refresh_token, $google->goo_refresh_token);
//     }

//     /** @test  */
//     public function should_authenticate()
//     {
//         $request = Request::create('/', 'POST', [
//             'code' => 'codemock',
//         ]);

//         $responseMock = $this->mockAccessTokenResponse();
//         $this->apiGoogleMock->shouldReceive('accessToken')
//             ->once()
//             ->andReturn($responseMock);

//         $response = $this->service->auth($request);

//         $this->assertInstanceOf(Google::class, $response);
//         $this->assertNotNull($response->goo_id);
//         $this->assertEquals($response->goo_token_type, $responseMock['token_type']);
//         $this->assertEquals($response->goo_expires_in, $responseMock['expires_in']);
//         $this->assertEquals($response->goo_access_token, $responseMock['access_token']);
//         $this->assertEquals($response->goo_refresh_token, $responseMock['refresh_token']);
//     }

//     /** @test  */
//     public function should_list_items()
//     {
//         Google::factory()->create();

//         $this->apiGoogleMock->shouldReceive('allProducts')
//             ->twice()
//             ->andReturn(['error' => ['code' => 401]], []);

//         $this->apiGoogleMock->shouldReceive('refreshToken')
//             ->once()
//             ->andReturn($this->mockAccessTokenResponse());

//         $response = $this->service->index();

//         $this->assertIsArray($response);
//         $this->assertEmpty($response);

//         $this->apiGoogleMock->shouldHaveReceived('allProducts')->times(2);
//     }

//     /** @test  */
//     public function should_find_a_item()
//     {
//         Google::factory()->create();

//         $this->apiGoogleMock->shouldReceive('getProduct')
//             ->twice()
//             ->andReturn(['error' => ['code' => 401]], []);

//         $this->apiGoogleMock->shouldReceive('refreshToken')
//             ->once()
//             ->andReturn($this->mockAccessTokenResponse());

//         $response = $this->service->findById('prodA');

//         $this->assertIsArray($response);
//         $this->assertEmpty($response);

//         $this->apiGoogleMock->shouldHaveReceived('getProduct')->times(2);
//     }

//     /** @test  */
//     public function should_store_item()
//     {
//         Google::factory()->create();

//         $this->apiGoogleMock->shouldReceive('addProduct')
//             ->twice()
//             ->andReturn(['error' => ['code' => 401]], []);

//         $this->apiGoogleMock->shouldReceive('refreshToken')
//             ->once()
//             ->andReturn($this->mockAccessTokenResponse());

//         $response = $this->service->addProduct([
//             'offerId' => 'offerIdMock',
//             'title' => 'titleMock',
//             'description' => 'descriptionMock',
//             'link' => 'linkMock',
//             'imageLink' => 'imageLinkMock',
//             'additionalImageLinks' => 'additionalImageLinksMock',
//             'price' => 'priceMock',
//         ]);

//         $this->assertIsArray($response);
//         $this->assertEmpty($response);

//         $this->apiGoogleMock->shouldHaveReceived('addProduct')->times(2);
//     }

//     /** @test  */
//     public function should_destroy_item()
//     {
//         Google::factory()->create();

//         $this->apiGoogleMock->shouldReceive('deleteProduct')
//             ->twice()
//             ->andReturn(['error' => ['code' => 401]], []);

//         $this->apiGoogleMock->shouldReceive('refreshToken')
//             ->once()
//             ->andReturn($this->mockAccessTokenResponse());

//         $response = $this->service->destroy('prodA');

//         $this->assertIsArray($response);
//         $this->assertEmpty($response);

//         $this->apiGoogleMock->shouldHaveReceived('deleteProduct')->times(2);
//     }

//     private function mockAccessTokenResponse()
//     {
//         return [
//             'token_type' => 'Bearer',
//             'expires_in' => 3599,
//             'access_token' => 'token123',
//             'refresh_token' => 'refreshtoken123',
//         ];
//     }
// }
