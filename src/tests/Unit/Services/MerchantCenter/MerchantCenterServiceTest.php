<?php

namespace Tests\Unit\Services\MerchantCenter;

use App\Api\ApiMerchantCenter;
use App\Models\MerchantCenter;
use App\Services\MerchantCenter\MerchantCenterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class MerchantCenterServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $apiMerchantCenterMock;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->apiMerchantCenterMock = $this->mock(ApiMerchantCenter::class)
            ->makePartial();

        $this->service = new MerchantCenterService(
            new MerchantCenter(),
            $this->apiMerchantCenterMock
        );
    }

    /** @test  */
    public function should_find_a_first_item()
    {
        $merchantCenter = MerchantCenter::factory()->create();

        $response = $this->service->firstMerchantCenter();

        $this->assertInstanceOf(MerchantCenter::class, $response);
        $this->assertNotNull($response->mec_id);
        $this->assertEquals($response->mec_token_type, $merchantCenter->mec_token_type);
        $this->assertEquals($response->mec_expires_in, $merchantCenter->mec_expires_in);
        $this->assertEquals($response->mec_access_token, $merchantCenter->mec_access_token);
        $this->assertEquals($response->mec_refresh_token, $merchantCenter->mec_refresh_token);
        $this->assertEquals($response->mec_authorize_code, $merchantCenter->mec_authorize_code);
    }

    /** @test  */
    public function should_authenticate()
    {
        $request = Request::create('/', 'POST', [
            'code' => 'codemock',
        ]);

        $responseMock = $this->mockAccessTokenResponse();
        $this->apiMerchantCenterMock->shouldReceive('accessToken')
            ->once()
            ->andReturn($responseMock);

        $response = $this->service->auth($request);

        $this->assertInstanceOf(MerchantCenter::class, $response);
        $this->assertNotNull($response->mec_id);
        $this->assertEquals($response->mec_token_type, $responseMock->token_type);
        $this->assertEquals($response->mec_expires_in, $responseMock->expires_in);
        $this->assertEquals($response->mec_access_token, $responseMock->access_token);
        $this->assertEquals($response->mec_refresh_token, $responseMock->refresh_token);
        $this->assertEquals($response->mec_authorize_code, 'codemock');
    }

    /** @test  */
    public function should_list_items()
    {
        MerchantCenter::factory()->create();

        $this->apiMerchantCenterMock->shouldReceive('allProducts')
            ->twice()
            ->andReturn(['error' => ['code' => 401]], []);

        $this->apiMerchantCenterMock->shouldReceive('refreshToken')
            ->once()
            ->andReturn($this->mockAccessTokenResponse());

        $response = $this->service->index();

        $this->assertIsArray($response);
        $this->assertEmpty($response);

        $this->apiMerchantCenterMock->shouldHaveReceived('allProducts')->times(2);
    }

    /** @test  */
    public function should_find_a_item()
    {
        MerchantCenter::factory()->create();

        $this->apiMerchantCenterMock->shouldReceive('getProduct')
            ->twice()
            ->andReturn(['error' => ['code' => 401]], []);

        $this->apiMerchantCenterMock->shouldReceive('refreshToken')
            ->once()
            ->andReturn($this->mockAccessTokenResponse());

        $response = $this->service->findById('prodA');

        $this->assertIsArray($response);
        $this->assertEmpty($response);

        $this->apiMerchantCenterMock->shouldHaveReceived('getProduct')->times(2);
    }

    /** @test  */
    public function should_store_item()
    {
        MerchantCenter::factory()->create();

        $this->apiMerchantCenterMock->shouldReceive('addProduct')
            ->twice()
            ->andReturn(['error' => ['code' => 401]], []);

        $this->apiMerchantCenterMock->shouldReceive('refreshToken')
            ->once()
            ->andReturn($this->mockAccessTokenResponse());

        $response = $this->service->addProduct([
            'offerId' => 'offerIdMock',
            'title' => 'titleMock',
            'description' => 'descriptionMock',
            'link' => 'linkMock',
            'imageLink' => 'imageLinkMock',
            'additionalImageLinks' => 'additionalImageLinksMock',
            'price' => 'priceMock',
        ]);

        $this->assertIsArray($response);
        $this->assertEmpty($response);

        $this->apiMerchantCenterMock->shouldHaveReceived('addProduct')->times(2);
    }

    /** @test  */
    public function should_destroy_item()
    {
        MerchantCenter::factory()->create();

        $this->apiMerchantCenterMock->shouldReceive('deleteProduct')
            ->twice()
            ->andReturn(['error' => ['code' => 401]], []);

        $this->apiMerchantCenterMock->shouldReceive('refreshToken')
            ->once()
            ->andReturn($this->mockAccessTokenResponse());

        $response = $this->service->destroy('prodA');

        $this->assertIsArray($response);
        $this->assertEmpty($response);

        $this->apiMerchantCenterMock->shouldHaveReceived('deleteProduct')->times(2);
    }

    private function mockAccessTokenResponse()
    {
        $data = [
            'token_type' => 'Bearer',
            'expires_in' => 3599,
            'access_token' => 'token123',
            'refresh_token' => 'refreshtoken123',
        ];

        return json_decode(json_encode($data));
    }
}
