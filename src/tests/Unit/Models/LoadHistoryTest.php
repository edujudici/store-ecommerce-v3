<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class LoadHistoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function loads_history_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('loads_history', [
                'loh_id', 'loh_total', 'loh_account_title',
            ]),
            1
        );
    }
}
