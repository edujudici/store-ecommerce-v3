<?php

namespace Tests\Unit\Services\Freight;

use App\Api\ApiMelhorEnvio;
use App\Models\MelhorEnvio;
use App\Services\Freight\MelhorEnvioService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class MelhorEnvioServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $apiMelhorEnvioMock;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->apiMelhorEnvioMock = $this->mock(ApiMelhorEnvio::class)
            ->makePartial();

        $this->service = new MelhorEnvioService(
            new MelhorEnvio(),
            $this->apiMelhorEnvioMock
        );
    }

    /** @test  */
    public function should_authenticate()
    {
        $request = Request::create('/', 'POST', [
            'code' => 'codemock',
        ]);

        $responseMock = $this->mockAccessTokenResponse();
        $this->apiMelhorEnvioMock->shouldReceive('accessToken')
            ->once()
            ->andReturn($responseMock);

        $response = $this->service->auth($request);

        $this->assertInstanceOf(MelhorEnvio::class, $response);
        $this->assertNotNull($response->mee_id);
        $this->assertEquals($response->mee_token_type, $responseMock->token_type);
        $this->assertEquals($response->mee_expires_in, $responseMock->expires_in);
        $this->assertEquals($response->mee_access_token, $responseMock->access_token);
        $this->assertEquals($response->mee_refresh_token, $responseMock->refresh_token);
        $this->assertEquals($response->mee_authorize_code, 'codemock');
    }

    /** @test  */
    public function should_calculate_freight()
    {
        MelhorEnvio::factory()->create();

        $this->apiMelhorEnvioMock->shouldReceive('calculate')
            ->once()
            ->andReturn(['price' => '9.99']);

        $response = $this->service->calculate('99999999', '11111111', 1);

        $this->assertIsArray($response);
        $this->assertEquals($response['price'], '9.99');
    }

    /** @test  */
    public function should_calculate_freight_unauthorized()
    {
        MelhorEnvio::factory()->create();

        $this->apiMelhorEnvioMock->shouldReceive('calculate')
            ->twice()
            ->andReturn(['message' => 'Unauthenticated'], ['price' => '9.99']);

        $this->apiMelhorEnvioMock->shouldReceive('refreshToken')
            ->once()
            ->andReturn($this->mockAccessTokenResponse());

        $response = $this->service->calculate('99999999', '11111111', 1);

        $this->assertIsArray($response);
        $this->assertEquals($response['price'], '9.99');

        $this->apiMelhorEnvioMock->shouldHaveReceived('calculate')->times(2);
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
