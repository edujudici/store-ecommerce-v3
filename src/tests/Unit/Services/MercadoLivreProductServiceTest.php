<?php

namespace Tests\Unit\Services;

use App\Models\MercadoLivreComment;
use App\Models\MercadoLivreProduct;
use Tests\TestCase;
use App\Services\MercadoLivreProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @group ServiceTest
 */
class MercadoLivreProductServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new MercadoLivreProductService(new MercadoLivreProduct());
    }

    /** @test  */
    public function should_exists_item()
    {
        $comment = MercadoLivreComment::factory()->create();
        MercadoLivreProduct::factory()->create([
            'mep_item_id' => $comment->mec_item_id
        ]);

        $response = $this->service->exists($comment->mec_item_id);

        $this->assertTrue($response);
    }

    /** @test  */
    public function should_store_item()
    {
        $comment = MercadoLivreComment::factory()->create();
        $mockProduct = $this->mockProduct($comment->mec_item_id);

        $this->service->store($comment, $mockProduct);

        $this->assertInstanceOf(MercadoLivreProduct::class, $comment->product);
        $this->assertEquals($mockProduct->id, $comment->product->mep_item_id);
    }

    private function mockProduct($id)
    {
        $data =  [
            'id' => $id,
            'title' => $this->faker->title,
            'price' => $this->faker->randomFloat(2, 1, 100),
            'permalink' => $this->faker->url,
            'secure_thumbnail' => $this->faker->url,
        ];

        return json_decode(json_encode($data));
    }
}
