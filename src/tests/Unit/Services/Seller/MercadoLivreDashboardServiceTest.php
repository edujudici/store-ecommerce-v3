<?php

namespace Tests\Unit\Services\Seller;

use Tests\TestCase;
use App\Models\MercadoLivre;
use App\Services\Seller\MercadoLivreDashboardService;
use App\Services\Seller\MercadoLivreService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @group ServiceTest
 */
class MercadoLivreDashboardServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $mercadoLivreService;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->mercadoLivreService = $this->mock(MercadoLivreService::class)
            ->makePartial();

        $this->service = new MercadoLivreDashboardService(
            $this->mercadoLivreService
        );
    }

    /** @test  */
    public function should_list_items()
    {
        $mercadoLivre = MercadoLivre::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $mercadoLivre->mel_id,
        ]);

        $this->mercadoLivreService->shouldReceive('findById')
            ->once()
            ->andReturn($mercadoLivre);

        $response = $this->service->index($request);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('loadDay', $response);
        $this->assertArrayHasKey('answerDay', $response);
        $this->assertArrayHasKey('loadYesterday', $response);
        $this->assertArrayHasKey('answerYesterday', $response);
        $this->assertArrayHasKey('totalLoad', $response);
        $this->assertArrayHasKey('totalAnswered', $response);
        $this->assertArrayHasKey('totalAnsweredPartner', $response);
    }
}
