<?php

namespace Tests\Unit\Services\Seller;

use App\Models\MercadoLivreComment;
use App\Models\MercadoLivreUser;
use App\Services\Seller\MercadoLivreUserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class MercadoLivreUserServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new MercadoLivreUserService(new MercadoLivreUser());
    }

    /** @test  */
    public function should_exists_item()
    {
        $comment = MercadoLivreComment::factory()->create();
        MercadoLivreUser::factory()->create([
            'meu_user_id' => $comment->mec_from_id
        ]);

        $response = $this->service->exists($comment->mec_from_id);

        $this->assertTrue($response);
    }

    /** @test  */
    public function should_store_item()
    {
        $comment = MercadoLivreComment::factory()->create();
        $mockUser = $this->mockUser($comment->mec_from_id);

        $this->service->store($comment, $mockUser);

        $this->assertInstanceOf(MercadoLivreUser::class, $comment->user);
        $this->assertEquals($mockUser->id, $comment->user->meu_user_id);
    }

    private function mockUser($id)
    {
        $data =  [
            'id' => $id,
            'nickname' => $this->faker->name,
            'registration_date' => $this->faker->date,
            'address' => [
                'city' => $this->faker->city,
                'state' => 'BR-SP',
            ],
            'points' => $this->faker->randomFloat(2, 1, 100),
            'permalink' => $this->faker->url,
            'seller_reputation' => [
                'level_id' => '1_red',
                'power_seller_status' => '',
                'transactions' => [
                    'canceled' => $this->faker->randomNumber(2),
                    'completed' => $this->faker->randomNumber(2),
                    'total' => $this->faker->randomNumber(2),
                ],
            ]
        ];

        return json_decode(json_encode($data));
    }
}
