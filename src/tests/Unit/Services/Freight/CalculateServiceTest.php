<?php

namespace Tests\Unit\Services;

use App\Models\Company;
use App\Services\CompanyService;
use App\Services\Freight\CalculateService;
use App\Services\Freight\MelhorEnvioService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class CalculateServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $companyServiceMock;
    private $melhorEnvioServiceMock;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->companyServiceMock = $this->mock(CompanyService::class)
            ->makePartial();
        $this->melhorEnvioServiceMock = $this->mock(MelhorEnvioService::class)
            ->makePartial();

        $this->service = new CalculateService(
            $this->companyServiceMock,
            $this->melhorEnvioServiceMock
        );
    }

    /** @test  */
    public function should_calculate_return_only_pickup_at_home()
    {
        $request = Request::create('/', 'POST', [
            'zipcode' => '11111111',
            'value' => $this->faker->randomFloat(2, 1, 99),
        ]);

        $this->companyServiceMock->shouldReceive('index')
            ->once()
            ->andReturn(new Company());

        $this->melhorEnvioServiceMock->shouldReceive('calculate')
            ->once()
            ->andThrow(new Exception());

        $response = $this->service->calculate($request);

        $this->assertTrue(is_array($response));
        $this->assertCount(1, $response);
        $this->assertEquals($response[0]['id'], -1);
        $this->assertEquals($response[0]['name'], 'Retirar no Local');
        $this->assertEquals($response[0]['price'], 0);
        $this->assertEquals($response[0]['deliveryTime'], 4);
        $this->assertEquals($response[0]['deliveryRange']['min'], 3);
        $this->assertEquals($response[0]['deliveryRange']['max'], 4);
    }

    /** @test  */
    public function should_calculate_return_multiples_freight()
    {
        $request = Request::create('/', 'POST', [
            'zipcode' => '11111111',
            'value' => $this->faker->randomFloat(2, 1, 99),
        ]);

        $calculateData = [[
            'id' => 1,
            'name' => 'Sedex',
            'price' => 11.50,
            'delivery_time' => 2,
            'delivery_range' => [
                'min' => 1,
                'max' => 2,
            ],
            'company' => [
                'id' => 1,
                'name' => 'Correios',
                'picture' => 'https://correios.com.br',
            ]
        ]];

        $this->companyServiceMock->shouldReceive('index')
            ->once()
            ->andReturn(new Company());

        $this->melhorEnvioServiceMock->shouldReceive('calculate')
            ->once()
            ->andReturn($calculateData);

        $response = $this->service->calculate($request);

        $this->assertTrue(is_array($response));
        $this->assertCount(2, $response);
        $this->assertEquals($response[0]['id'], 1);
        $this->assertEquals($response[0]['name'], 'Sedex');
        $this->assertEquals($response[0]['price'], 11.50);
        $this->assertEquals($response[0]['deliveryTime'], 2);
        $this->assertEquals($response[0]['deliveryRange']['min'], 1);
        $this->assertEquals($response[0]['deliveryRange']['max'], 2);
        $this->assertEquals($response[0]['company']['id'], 1);
        $this->assertEquals($response[0]['company']['name'], 'Correios');
        $this->assertEquals($response[0]['company']['picture'], 'https://correios.com.br');
        $this->assertEquals($response[1]['id'], -1);
        $this->assertEquals($response[1]['name'], 'Retirar no Local');
        $this->assertEquals($response[1]['price'], 0);
        $this->assertEquals($response[1]['deliveryTime'], 4);
        $this->assertEquals($response[1]['deliveryRange']['min'], 3);
        $this->assertEquals($response[1]['deliveryRange']['max'], 4);
    }
}
