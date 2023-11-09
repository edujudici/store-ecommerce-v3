<?php

namespace Tests\Unit\Services;

use App\Api\MercadoLibre;
use Tests\TestCase;
use App\Models\Seller;
use App\Services\Seller\SellerService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @group ServiceTest
 */
class SellerServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $apiMercadoLibreMock;
    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->apiMercadoLibreMock = $this->mock(MercadoLibre::class)
            ->makePartial();

        $this->service = new SellerService(new Seller(), $this->apiMercadoLibreMock);
    }

    /** @test  */
    public function should_list_items()
    {
        Seller::factory()->count(3)->create();

        $response = $this->service->index();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_store_item()
    {
        $nickname = $this->faker->word;

        $response = $this->service->store($nickname);

        $this->assertInstanceOf(Seller::class, $response);
        $this->assertEquals($nickname, $response->sel_nickname);
    }

    /** @test  */
    public function should_destroy_item()
    {
        $seller = Seller::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $seller->sel_id,
        ]);

        $response = $this->service->destroy($request);

        $this->assertTrue($response);
    }

    /** @test  */
    public function should_search_item_empty_seller()
    {
        $request = Request::create('/', 'POST', [
            'nickname' => $this->faker->firstName,
        ]);
        $this->apiMercadoLibreMock->shouldReceive('getUserDetailsByNickname')
            ->once()
            ->andReturn($this->mockSeller());

        $response = $this->service->search($request);

        $this->assertEmpty($response->seller->nickname);
    }

    /** @test  */
    public function should_search_item_not_empty_seller()
    {
        $request = Request::create('/', 'POST', [
            'nickname' => $this->faker->firstName,
        ]);
        $this->apiMercadoLibreMock->shouldReceive('getUserDetailsByNickname')
            ->once()
            ->andReturn($this->mockSeller($request->input('nickname')));

        $response = $this->service->search($request);

        $this->assertEquals($request->input('nickname'), $response->seller->nickname);
    }

    private function mockSeller($nickname = '')
    {
        $data = [
            'seller' => [
                'nickname' => $nickname
            ]
        ];

        return json_decode(json_encode($data));
    }
}
