<?php

namespace Tests\Unit\Services;

use App\Services\DashboardNewShoppersService;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class DashboardNewShoppersServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new DashboardNewShoppersService(new User());
    }

    /** @test  */
    public function should_list_items()
    {
        User::factory()->count(15)->create([
            'role' => 'shopper'
        ]);

        $response = $this->service->index();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(10, $response);
    }
}
