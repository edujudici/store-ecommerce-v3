<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Banner;
use App\Services\BannerService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @group ServiceTest
 */
class BannerServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new BannerService(new Banner());
    }

    /** @test  */
    public function should_list_items()
    {
        Banner::factory()->count(3)->create();

        $response = $this->service->index();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_find_a_item()
    {
        $banner = Banner::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $banner->ban_id,
        ]);

        $response = $this->service->findById($request);

        $this->assertInstanceOf(Banner::class, $response);
        $this->assertEquals($response->ban_id, $banner->ban_id);
        $this->assertEquals($response->ban_title, $banner->ban_title);
        $this->assertEquals($response->ban_description, $banner->ban_description);
        $this->assertEquals($response->ban_image, $banner->ban_image);
        $this->assertEquals($response->ban_url, $banner->ban_url);
    }

    /** @test  */
    public function should_store_item()
    {
        $request = Request::create('/', 'POST', [
            'title' => $this->faker->title,
            'description' => $this->faker->title,
            'file' => UploadedFile::fake()->image('fake.png'),
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(Banner::class, $response);
        $this->assertEquals($request->input('title'), $response->ban_title);
        $this->assertEquals($request->input('description'), $response->ban_description);
    }

    /** @test  */
    public function should_update_item()
    {
        $banner = Banner::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $banner->ban_id,
            'title' => $banner->ban_title,
            'description' => $banner->ban_description,
            // 'file' => UploadedFile::fake()->image('fake.png'),
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(Banner::class, $response);
        $this->assertEquals($request->input('id'), $response->ban_id);
        $this->assertEquals($request->input('title'), $response->ban_title);
        $this->assertEquals($request->input('description'), $response->ban_description);
    }

    /** @test  */
    public function should_destroy_item()
    {
        $banner = Banner::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $banner->ban_id,
        ]);

        $response = $this->service->destroy($request);

        $this->assertTrue($response);
    }
}
