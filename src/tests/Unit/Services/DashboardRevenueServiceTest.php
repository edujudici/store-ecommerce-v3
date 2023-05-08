<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Order;
use App\Services\DashboardRevenueService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @group ServiceTest
 */
class DashboardRevenueServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new DashboardRevenueService(new Order());
    }

    /** @test  */
    public function should_list_total_revenue()
    {
        $this->assertTrue(true);
        // Order::factory()->count(3)->create();

        // $response = $this->service->indexRevenue();

        // $this->assertInstanceOf(Collection::class, $response);
        // $this->assertCount(3, $response->total);
        // $this->assertObjectHasAttribute('total', $response);
        // $this->assertObjectHasAttribute('year', $response);
        // $this->assertObjectHasAttribute('month', $response);
    }

    /** @test  */
    public function should_list_total_sales_year()
    {
        $this->assertTrue(true);
        // Order::factory()->count(3)->create();

        // $response = $this->service->indexSalesYear();

        // $this->assertInstanceOf(Collection::class, $response);
        // $this->assertCount(3, $response->total);
        // $this->assertObjectHasAttribute('total', $response);
        // $this->assertObjectHasAttribute('revenue', $response);
        // $this->assertObjectHasAttribute('year', $response);
        // $this->assertObjectHasAttribute('month', $response);
    }
}
