<?php

namespace Tests\Unit\Services\User;

use App\Models\User;
use App\Models\UserSession;
use App\Services\User\UserSessionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class UserSessionServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new UserSessionService(new UserSession());
    }

    /** @test  */
    public function should_find_a_item()
    {
        $user = User::factory()->create();
        $userSession = UserSession::factory()->create([
            'user_id' => $user->id,
            'uss_type' => 'favorite',
        ]);

        $response = $this->service->findByUser($user->id);

        $this->assertInstanceOf(UserSession::class, $response);
        $this->assertEquals($response->user_id, $userSession->user_id);
        $this->assertEquals($response->uss_type, $userSession->uss_type);
        $this->assertEquals($response->uss_json, $userSession->uss_json);
    }

    /** @test  */
    public function should_store_item()
    {
        $user = User::factory()->create();
        $json = '{"nome": "test"}';

        $response = $this->service->store($user, $json);

        $this->assertInstanceOf(UserSession::class, $response);
        $this->assertInstanceOf(UserSession::class, $user->favorite);
        $this->assertEquals($response->user_id, $response->user_id);
        $this->assertEquals($response->uss_type, $response->uss_type);
        $this->assertEquals($response->uss_json, $response->uss_json);
    }
}
