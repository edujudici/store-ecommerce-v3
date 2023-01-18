<?php

namespace Tests\Unit\Services;

use App\Api\CalculaFrete;
use App\Services\FreightService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class FreightServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->calculaFreteMock = $this->mock(CalculaFrete::class)
            ->makePartial();

        $this->service = new FreightService($this->calculaFreteMock);
    }

    /** @test  */
    public function should_get_codes()
    {
        $response = $this->service->getCodes();

        $this->assertIsArray($response);
    }

    /** @test  */
    public function should_calculate_freight_value_error()
    {
        $request = Request::create('/', 'POST', [
            'zipcode' => $this->faker->postcode,
            'serviceCode' => '04014',
        ]);

        $this->calculaFreteMock->shouldReceive('init')
            ->once();

        $this->calculaFreteMock->shouldReceive('request')
            ->once()
            ->andReturn($this->mockFreightServiceError());

        $response = $this->service->calculate($request);

        $this->assertNull($response);
    }

    /** @test  */
    public function should_calculate_freight_value()
    {
        $request = Request::create('/', 'POST', [
            'zipcode' => $this->faker->postcode,
            'serviceCode' => '04014',
        ]);

        $freightService = $this->mockFreightService();

        $this->calculaFreteMock->shouldReceive('init')
            ->once();

        $this->calculaFreteMock->shouldReceive('request')
            ->once()
            ->andReturn($freightService);

        $response = $this->service->calculate($request);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('ServicoNome', $response);
        $this->assertArrayHasKey('PrazoEntrega', $response);
        $this->assertEquals($freightService['PrazoEntrega'] + 4, $response['PrazoEntrega']);
        $this->assertEquals('Sedex', $response['ServicoNome']);
    }

    private function mockFreightServiceError()
    {
        return [
            'Erro' => 2,
        ];
    }

    private function mockFreightService()
    {
        return [
            'Erro' => 0,
            'PrazoEntrega' => $this->faker->randomNumber(2),
        ];
    }
}
