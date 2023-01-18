<?php

namespace Tests\Unit\Services;

use App\Models\Order;
use App\Services\DashboardOrdersOverviewService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class DashboardOrdersOverviewServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new DashboardOrdersOverviewService(new Order());
    }

    /** @test  */
    public function should_list_items()
    {
        Order::factory()->count(25)->create();

        $response = $this->service->index();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('cancelled', $response);
        $this->assertArrayHasKey('finished', $response);
        $this->assertArrayHasKey('incomplete', $response);
        $this->assertArrayHasKey('pending', $response);
    }
}
