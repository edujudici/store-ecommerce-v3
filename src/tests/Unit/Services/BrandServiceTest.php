<?php

namespace Tests\Unit\Services;

use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class BrandServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new BrandService(new Brand());
    }

    /** @test  */
    public function should_list_items()
    {
        Brand::factory()->count(3)->create();

        $response = $this->service->index();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_find_a_item()
    {
        $brand = Brand::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $brand->bra_id,
        ]);

        $response = $this->service->findById($request);

        $this->assertInstanceOf(Brand::class, $response);
        $this->assertEquals($response->bra_id, $brand->bra_id);
        $this->assertEquals($response->bra_image, $brand->bra_image);
    }

    /** @test  */
    public function should_store_item()
    {
        $request = Request::create('/', 'POST', [
            'title' => $this->faker->word,
            'file' => UploadedFile::fake()->image('fake.png'),
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(Brand::class, $response);
        $this->assertEquals($request->input('title'), $response->bra_title);
    }

    /** @test  */
    public function should_update_item()
    {
        $brand = Brand::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $brand->bra_id,
            'title' => $this->faker->word,
            // 'file' => UploadedFile::fake()->image('fake.png'),
        ]);

        $response = $this->service->store($request);

        $this->assertInstanceOf(Brand::class, $response);
        $this->assertEquals($request->input('id'), $response->bra_id);
        $this->assertEquals($request->input('title'), $response->bra_title);
    }

    /** @test  */
    public function should_destroy_item()
    {
        $brand = Brand::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $brand->bra_id,
        ]);

        $response = $this->service->destroy($request);

        $this->assertTrue($response);
    }
}
