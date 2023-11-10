<?php

namespace Tests\Unit\Services\Painel;

use App\Models\Company;
use App\Services\Painel\CompanyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class CompanyServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new CompanyService(new Company());
    }

    /** @test  */
    public function should_list_items()
    {
        $company = Company::factory()->create();

        $response = $this->service->index();

        $this->assertInstanceOf(Company::class, $response);
        $this->assertEquals($response->com_id, $company->com_id);
    }

    /** @test  */
    public function should_store_item()
    {
        $request = Request::create('/', 'POST', [
            'title' => $this->faker->title,
            'description' => $this->faker->title,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'workHours' => 'aberto das 8h até as 18h',
            'mail' => $this->faker->email,
            'zipcode' => '00000000',
            'number' => $this->faker->randomNumber(2),
            'district' => $this->faker->streetName,
            'city' => $this->faker->city,
            'uf' => 'SP',
            // 'file' => UploadedFile::fake()->image('fake.png'),
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(Company::class, $response);
        $this->assertEquals($request->input('title'), $response->com_title);
        $this->assertEquals($request->input('description'), $response->com_description);
        $this->assertEquals($request->input('phone'), $response->com_phone);
        $this->assertEquals($request->input('mail'), $response->com_mail);
        $this->assertEquals($request->input('workHours'), $response->com_work_hours);
        $this->assertEquals($request->input('address'), $response->com_address);
        $this->assertEquals($request->input('zipcode'), $response->com_zipcode);
        $this->assertEquals($request->input('number'), $response->com_number);
        $this->assertEquals($request->input('district'), $response->com_district);
        $this->assertEquals($request->input('city'), $response->com_city);
        $this->assertEquals($request->input('complement'), $response->com_complement);
        $this->assertEquals($request->input('uf'), $response->com_uf);
    }

    /** @test  */
    public function should_update_item()
    {
        $company = Company::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $company->com_id,
            'title' => $this->faker->title,
            'description' => $this->faker->title,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'workHours' => 'aberto das 8h até as 18h',
            'mail' => $this->faker->email,
            'zipcode' => '00000000',
            'number' => $this->faker->randomNumber(2),
            'district' => $this->faker->streetName,
            'city' => $this->faker->city,
            'uf' => 'SP',
            // 'file' => UploadedFile::fake()->image('fake.png'),
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(Company::class, $response);
        $this->assertEquals($request->input('id'), $response->com_id);
        $this->assertEquals($request->input('title'), $response->com_title);
        $this->assertEquals($request->input('description'), $response->com_description);
        $this->assertEquals($request->input('phone'), $response->com_phone);
        $this->assertEquals($request->input('mail'), $response->com_mail);
        $this->assertEquals($request->input('workHours'), $response->com_work_hours);
        $this->assertEquals($request->input('address'), $response->com_address);
        $this->assertEquals($request->input('zipcode'), $response->com_zipcode);
        $this->assertEquals($request->input('number'), $response->com_number);
        $this->assertEquals($request->input('district'), $response->com_district);
        $this->assertEquals($request->input('city'), $response->com_city);
        $this->assertEquals($request->input('complement'), $response->com_complement);
        $this->assertEquals($request->input('uf'), $response->com_uf);
    }
}
