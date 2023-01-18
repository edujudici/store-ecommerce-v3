<?php

namespace Tests\Unit\Services;

use App\Models\Order;
use App\Models\OrderHistory;
use App\Services\DashboardRecentOrdersService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class DashboardRecentOrdersServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new DashboardRecentOrdersService(new Order());
    }

    /** @test  */
    public function should_list_items()
    {
        Order::factory()->count(10)->create()->each(function ($model) {
            $model->histories()->save(OrderHistory::factory()->make());
        });

        $response = $this->service->index();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(5, $response);
        $this->assertCount(1, $response[0]->histories);
    }
}
