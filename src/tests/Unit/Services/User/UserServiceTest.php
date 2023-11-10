<?php

namespace Tests\Unit\Services\User;

use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use stdClass;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class UserServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new UserService(new User());
    }

    /** @test  */
    public function should_list_items()
    {
        User::factory()->count(3)->create();

        $response = $this->service->index();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_store_item()
    {
        $request = [
            'name' => $this->faker->firstName,
            'email' => $this->faker->email,
            'surname' => $this->faker->lastName,
        ];

        $response = $this->service->store($request);

        $this->assertInstanceOf(User::class, $response);
        $this->assertNotNull($response->uuid);
        $this->assertEquals('shopper', $response->role);
        $this->assertEquals($request['name'], $response->name);
        $this->assertEquals($request['email'], $response->email);
        $this->assertEquals($request['surname'], $response->surname);
    }

    /** @test  */
    public function should_store_item_by_socialite()
    {
        $request = $this->userMock();

        $response = $this->service->storeBySocialite($request);

        $this->assertInstanceOf(User::class, $response);
        $this->assertNotNull($response->uuid);
        $this->assertEquals('shopper', $response->role);
        $this->assertEquals($request->email, $response->email);
        $this->assertEquals($request->user['given_name'], $response->name);
        $this->assertEquals($request->user['family_name'], $response->surname);
    }

    /** @test  */
    public function should_update_item_by_socialite()
    {
        $user = User::factory()->create([
            'google_id' => null,
            'role' => 'shopper',
        ]);

        $request = $this->userMock($user);

        $response = $this->service->storeBySocialite($request);

        $this->assertInstanceOf(User::class, $response);
        $this->assertNotNull($response->uuid);
        $this->assertEquals($request->id, $response->google_id);
    }

    private function userMock($user = null): stdClass
    {
        $obj = new stdClass();
        $obj->id = $this->faker->uuid;
        $obj->email = $user ? $user->email : $this->faker->email;
        $obj->user = [
            'given_name' => $user ? $user->name : $this->faker->firstName,
            'family_name' => $user ? $user->surname : $this->faker->lastName,
        ];
        return $obj;
    }
}
