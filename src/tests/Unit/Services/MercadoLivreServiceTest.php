<?php

namespace Tests\Unit\Services;

use App\Api\MercadoLibre;
use App\Models\MercadoLivre;
use App\Services\MercadoLivreService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use stdClass;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class MercadoLivreServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $apiMercadoLibreMock;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->apiMercadoLibreMock = $this->mock(MercadoLibre::class)
            ->makePartial();

        $this->service = new MercadoLivreService(
            new MercadoLivre(),
            $this->apiMercadoLibreMock
        );
    }

    /** @test  */
    public function should_dispatch_account()
    {
        $request = Request::create('/', 'POST', [
            'code' => $this->faker->word,
        ]);

        $this->mock(MercadoLivreService::class)
            ->makePartial()
            ->shouldReceive('newAccount')
            ->once();

        $this->service->dispatchAccount($request);
    }

    /** @test  */
    public function should_new_account()
    {
        $request = [
            'code' => $this->faker->word,
        ];

        $this->apiMercadoLibreMock->shouldReceive('accessToken')
            ->once();
        $this->apiMercadoLibreMock->shouldReceive('getUserDetails')
            ->once()
            ->andReturn($this->mockUserData('ACCOUNTTEST'));

        $mercadoLivre = $this->service->newAccount($request);

        $this->assertEquals($mercadoLivre->mel_title, 'ACCOUNTTEST');
        $this->assertEquals($mercadoLivre->mel_code_tg, $request['code']);
    }

    /** @test  */
    public function should_list_items()
    {
        MercadoLivre::factory()->count(3)->create();

        $response = $this->service->index();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_find_a_item()
    {
        $mercadoLivre = MercadoLivre::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $mercadoLivre->mel_id,
        ]);

        $response = $this->service->findById($request);

        $this->assertInstanceOf(MercadoLivre::class, $response);
        $this->assertEquals($response->mel_id, $mercadoLivre->mel_id);
        $this->assertEquals($response->mel_title, $mercadoLivre->mel_title);
        $this->assertEquals($response->mel_code_tg, $mercadoLivre->mel_code_tg);
        $this->assertEquals($response->mel_access_token, $mercadoLivre->mel_access_token);
        $this->assertEquals($response->mel_token_type, $mercadoLivre->mel_token_type);
        $this->assertEquals($response->mel_expires_in, $mercadoLivre->mel_expires_in);
        $this->assertEquals($response->mel_scope, $mercadoLivre->mel_scope);
        $this->assertEquals($response->mel_user_id, $mercadoLivre->mel_user_id);
        $this->assertEquals($response->mel_refresh_token, $mercadoLivre->mel_refresh_token);
        $this->assertEquals($response->mel_after_sales_message, $mercadoLivre->mel_after_sales_message);
        $this->assertEquals(boolval($response->mel_after_sales_enabled), boolval($mercadoLivre->mel_after_sales_enabled));
    }

    /** @test  */
    public function should_store_item()
    {
        $request = Request::create('/', 'POST', [
            'mel_code_tg' => $this->faker->word,
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(MercadoLivre::class, $response);
        $this->assertEquals($request->input('mel_code_tg'), $response->mel_code_tg);
    }

    /** @test  */
    public function should_update_item()
    {
        $mercadoLivre = MercadoLivre::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $mercadoLivre->mel_id,
            'code' => 'CODE-TG-ABC123',
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(MercadoLivre::class, $response);
        $this->assertEquals($request->input('id'), $response->mel_id);
        $this->assertEquals($request->input('code'), 'CODE-TG-ABC123');
    }

    /** @test  */
    public function should_destroy_item()
    {
        $mercadoLivre = MercadoLivre::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $mercadoLivre->mel_id,
        ]);

        $response = $this->service->destroy($request);

        $this->assertTrue($response);
    }

    private function mockUserData($nickname)
    {
        $userObj = new stdClass;
        $userObj->nickname = $nickname;
        return $userObj;
    }
}
