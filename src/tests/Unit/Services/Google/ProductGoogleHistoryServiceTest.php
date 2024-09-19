<?php

namespace Tests\Unit\Services\Google;

use App\Models\ProductGoogleHistory;
use App\Services\Google\ProductGoogleHistoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class ProductGoogleHistoryServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new ProductGoogleHistoryService(new ProductGoogleHistory());
    }

    /** @test  */
    public function should_list_items()
    {
        ProductGoogleHistory::factory()->count(3)->create();

        $response = $this->service->index();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_store_item()
    {
        $loadDate = $this->faker->date();
        $total = $this->faker->randomNumber(2);
        $accountTitle = $this->faker->word;

        $response = $this->service->store($loadDate, $total, $accountTitle);

        $this->assertInstanceOf(ProductGoogleHistory::class, $response);
        $this->assertNotNull($response->pgh_id);
        $this->assertEquals($total, $response->pgh_total);
        $this->assertEquals($accountTitle, $response->pgh_account_title);
    }
}
