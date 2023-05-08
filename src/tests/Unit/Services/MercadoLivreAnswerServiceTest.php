<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\MercadoLivreAnswer;
use App\Services\MercadoLivreAnswerService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @group ServiceTest
 */
class MercadoLivreAnswerServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new MercadoLivreAnswerService(new MercadoLivreAnswer());
    }

    /** @test  */
    public function should_list_items()
    {
        MercadoLivreAnswer::factory()->count(3)->create();

        $response = $this->service->index();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_find_a_item()
    {
        $mercadoLivreAnswer = MercadoLivreAnswer::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $mercadoLivreAnswer->mea_id,
        ]);

        $response = $this->service->findById($request);

        $this->assertInstanceOf(MercadoLivreAnswer::class, $response);
        $this->assertEquals($response->mea_id, $mercadoLivreAnswer->mea_id);
        $this->assertEquals($response->mea_description, $mercadoLivreAnswer->mea_description);
    }

    /** @test  */
    public function should_store_item()
    {
        $request = Request::create('/', 'POST', [
            'description' => $this->faker->title,
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(MercadoLivreAnswer::class, $response);
        $this->assertEquals($request->input('description'), $response->mea_description);
    }

    /** @test  */
    public function should_update_item()
    {
        $mercadoLivreAnswer = MercadoLivreAnswer::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $mercadoLivreAnswer->mea_id,
            'description' => $mercadoLivreAnswer->mea_description,
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(MercadoLivreAnswer::class, $response);
        $this->assertEquals($request->input('id'), $response->mea_id);
        $this->assertEquals($request->input('description'), $response->mea_description);
    }

    /** @test  */
    public function should_destroy_item()
    {
        $mercadoLivreAnswer = MercadoLivreAnswer::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $mercadoLivreAnswer->mea_id,
        ]);

        $response = $this->service->destroy($request);

        $this->assertTrue($response);
    }
}
