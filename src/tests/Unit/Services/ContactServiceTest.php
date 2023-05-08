<?php

namespace Tests\Unit\Services;

use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class ContactServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new ContactService(new Contact());
    }

    /** @test  */
    public function should_list_items()
    {
        Contact::factory()->count(3)->create();

        $response = $this->service->index();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_find_a_item()
    {
        $contact = Contact::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $contact->con_id,
        ]);

        $response = $this->service->findById($request);

        $this->assertInstanceOf(Contact::class, $response);
        $this->assertEquals($response->con_id, $contact->con_id);
        $this->assertEquals($response->con_name, $contact->con_name);
        $this->assertEquals($response->con_email, $contact->con_email);
        $this->assertEquals($response->con_subject, $contact->con_subject);
        $this->assertEquals($response->con_message, $contact->con_message);
        $this->assertEquals($response->con_answer, $contact->con_answer);
    }

    /** @test  */
    public function should_store_item()
    {
        $request = Request::create('/', 'POST', [
            'name' => $this->faker->firstName,
            'email' => $this->faker->email,
            'subject' => $this->faker->title,
            'message' => $this->faker->title,
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(Contact::class, $response);
        $this->assertEquals($request->input('name'), $response->con_name);
        $this->assertEquals($request->input('email'), $response->con_email);
        $this->assertEquals($request->input('subject'), $response->con_subject);
        $this->assertEquals($request->input('message'), $response->con_message);
    }

    /** @test  */
    public function should_destroy_item()
    {
        $contact = Contact::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $contact->con_id,
        ]);

        $response = $this->service->destroy($request);

        $this->assertTrue($response);
    }
}
