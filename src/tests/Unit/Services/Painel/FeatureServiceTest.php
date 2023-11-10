<?php

namespace Tests\Unit\Services\Painel;

use App\Models\Feature;
use App\Services\Painel\FeatureService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class FeatureServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new FeatureService(new Feature());
    }

    /** @test  */
    public function should_list_items()
    {
        Feature::factory()->count(3)->create();

        $response = $this->service->index();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_find_a_item()
    {
        $feature = Feature::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $feature->fea_id,
        ]);

        $response = $this->service->findById($request);

        $this->assertInstanceOf(Feature::class, $response);
        $this->assertEquals($response->fea_id, $feature->fea_id);
        $this->assertEquals($response->fea_title, $feature->fea_title);
        $this->assertEquals($response->fea_image, $feature->fea_image);
        $this->assertEquals(
            $response->fea_description,
            $feature->fea_description
        );
    }

    /** @test  */
    public function should_store_item()
    {
        $request = Request::create('/', 'POST', [
            'title' => $this->faker->title,
            'description' => $this->faker->title,
            // 'file' => UploadedFile::fake()->image('fake.png'),
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(Feature::class, $response);
        $this->assertEquals($request->input('title'), $response->fea_title);
        $this->assertEquals($request->input('description'), $response->fea_description);
    }

    /** @test  */
    public function should_update_item()
    {
        $feature = Feature::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $feature->fea_id,
            'title' => $this->faker->title,
            'description' => $this->faker->title,
            // 'file' => UploadedFile::fake()->image('fake.png'),
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(Feature::class, $response);
        $this->assertEquals($request->input('id'), $response->fea_id);
        $this->assertEquals($request->input('title'), $response->fea_title);
        $this->assertEquals($request->input('description'), $response->fea_description);
    }

    /** @test  */
    public function should_destroy_item()
    {
        $feature = Feature::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $feature->fea_id,
        ]);

        $response = $this->service->destroy($request);

        $this->assertTrue($response);
    }
}
