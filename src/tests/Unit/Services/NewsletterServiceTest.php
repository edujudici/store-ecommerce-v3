<?php

namespace Tests\Unit\Services;

use App\Models\Newsletter;
use App\Services\Communication\NewsletterService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class NewsletterServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new NewsletterService(new Newsletter());
    }

    /** @test  */
    public function should_list_items()
    {
        Newsletter::factory()->count(3)->create();

        $response = $this->service->index();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_store_item()
    {
        $request = Request::create('/', 'POST', [
            'email' => $this->faker->email,
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(Newsletter::class, $response);
        $this->assertEquals($request->input('email'), $response->new_email);
    }

    /** @test  */
    public function should_destroy_item()
    {
        $newsletter = Newsletter::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $newsletter->new_id,
        ]);

        $response = $this->service->destroy($request);

        $this->assertTrue($response);
    }
}
