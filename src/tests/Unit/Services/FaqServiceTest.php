<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Faq;
use App\Services\FaqService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @group ServiceTest
 */
class FaqServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new FaqService(new Faq());
    }

    /** @test  */
    public function should_list_items()
    {
        Faq::factory()->count(3)->create();

        $response = $this->service->index();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_find_a_item()
    {
        $faq = Faq::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $faq->faq_id,
        ]);

        $response = $this->service->findById($request);

        $this->assertInstanceOf(Faq::class, $response);
        $this->assertEquals($response->faq_id, $faq->faq_id);
        $this->assertEquals($response->faq_title, $faq->faq_title);
        $this->assertEquals($response->faq_description, $faq->faq_description);
    }

    /** @test  */
    public function should_store_item()
    {
        $request = Request::create('/', 'POST', [
            'title' => $this->faker->title,
            'description' => $this->faker->title,
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(Faq::class, $response);
        $this->assertEquals($request->input('title'), $response->faq_title);
        $this->assertEquals($request->input('description'), $response->faq_description);
    }

    /** @test  */
    public function should_update_item()
    {
        $faq = Faq::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $faq->faq_id,
            'title' => $faq->faq_title,
            'description' => $faq->faq_description,
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(Faq::class, $response);
        $this->assertEquals($request->input('id'), $response->faq_id);
        $this->assertEquals($request->input('title'), $response->faq_title);
        $this->assertEquals($request->input('description'), $response->faq_description);
    }

    /** @test  */
    public function should_destroy_item()
    {
        $faq = Faq::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $faq->faq_id,
        ]);

        $response = $this->service->destroy($request);

        $this->assertTrue($response);
    }
}
