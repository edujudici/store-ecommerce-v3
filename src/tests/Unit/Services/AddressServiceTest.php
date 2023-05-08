<?php

namespace Tests\Unit\Services;

use App\Models\Address;
use App\Services\AddressService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class AddressServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new AddressService();
    }

    /** @test */
    public function should_create_new_item()
    {
        $user = User::factory()->create();
        $request = [
            'user_id' => $user->id,
            'adr_name' => $this->faker->firstName,
            'adr_surname' => $this->faker->lastName,
            'adr_phone' => '(99) 99999-9999',
            'adr_zipcode' => $this->faker->postcode,
            'adr_address' => $this->faker->address,
            'adr_number' => $this->faker->buildingNumber,
            'adr_district' => $this->faker->streetAddress,
            'adr_city' => $this->faker->city,
            'adr_complement' => $this->faker->word,
            'adr_uf' => 'SP',
            'adr_type' => 'shipping',
        ];
        $response = $this->service->store($user, $request);

        $this->assertInstanceOf(Address::class, $response);
        $this->assertEquals($request['adr_name'], $response->adr_name);
        $this->assertEquals($request['adr_surname'], $response->adr_surname);
        $this->assertEquals($request['adr_phone'], $response->adr_phone);
        $this->assertEquals($request['adr_zipcode'], $response->adr_zipcode);
        $this->assertEquals($request['adr_address'], $response->adr_address);
        $this->assertEquals($request['adr_number'], $response->adr_number);
        $this->assertEquals($request['adr_district'], $response->adr_district);
        $this->assertEquals($request['adr_city'], $response->adr_city);
        $this->assertEquals($request['adr_complement'], $response->adr_complement);
        $this->assertEquals($request['adr_uf'], $response->adr_uf);
        $this->assertEquals($request['adr_type'], $response->adr_type);
    }
}
