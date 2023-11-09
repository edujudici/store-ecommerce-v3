<?php

namespace Tests\Unit\Services\Seller;

use App\Models\LoadQuestionHistory;
use App\Services\Seller\LoadQuestionHistoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class LoadQuestionHistoryServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new LoadQuestionHistoryService(new LoadQuestionHistory());
    }

    /** @test  */
    public function should_list_items()
    {
        LoadQuestionHistory::factory()->count(12)->create();

        $response = $this->service->index();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(10, $response);
    }

    /** @test  */
    public function should_store_item()
    {
        $loadDate = date('Y-m-d H:i:s');
        $total = $this->faker->randomNumber(2);
        $totalSync = $this->faker->randomNumber(2);
        $accountId = $this->faker->randomNumber(9);
        $accountTitle = $this->faker->title;

        $response = $this->service->store(
            $loadDate,
            $total,
            $totalSync,
            $accountId,
            $accountTitle
        );

        $this->assertInstanceOf(LoadQuestionHistory::class, $response);
        $this->assertEquals($total, $response->lqh_total);
        $this->assertEquals($totalSync, $response->lqh_total_sync);
        $this->assertEquals($accountId, $response->lqh_account_id);
        $this->assertEquals($accountTitle, $response->lqh_account_title);
    }

    /** @test  */
    public function should_update_item()
    {
        $loadQuestionHistory = LoadQuestionHistory::factory()->create();

        $totalSync = 50;
        $this->service->update($loadQuestionHistory->lqh_id, $totalSync);

        $response = LoadQuestionHistory::find($loadQuestionHistory->lqh_id);

        $this->assertEquals($totalSync, $response->lqh_total_sync);
    }
}
