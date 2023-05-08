<?php

namespace Tests\Unit\Services;

use App\Models\LoadHistory;
use App\Services\LoadHistoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @group ServiceTest
 */
class LoadHistoryServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new LoadHistoryService(new LoadHistory());
    }

    /** @test  */
    public function should_list_items()
    {
        LoadHistory::factory()->count(3)->create();

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

        $this->assertInstanceOf(LoadHistory::class, $response);
        $this->assertNotNull($response->loh_id);
        $this->assertEquals($total, $response->loh_total);
        $this->assertEquals($accountTitle, $response->loh_account_title);
    }
}
