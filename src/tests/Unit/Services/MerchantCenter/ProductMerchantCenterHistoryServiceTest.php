<?php

namespace Tests\Unit\Services\MerchantCenter;

use App\Models\ProductMerchantCenterHistory;
use App\Services\MerchantCenter\ProductMerchantCenterHistoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class ProductMerchantCenterHistoryServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new ProductMerchantCenterHistoryService(new ProductMerchantCenterHistory());
    }

    /** @test  */
    public function should_list_items()
    {
        ProductMerchantCenterHistory::factory()->count(3)->create();

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

        $this->assertInstanceOf(ProductMerchantCenterHistory::class, $response);
        $this->assertNotNull($response->pmh_id);
        $this->assertEquals($total, $response->pmh_total);
        $this->assertEquals($accountTitle, $response->pmh_account_title);
    }
}
