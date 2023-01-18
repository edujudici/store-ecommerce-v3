<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class LoadQuestionHistoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function loads_questions_history_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('loads_questions_history', [
                'lqh_id',
                'lqh_total',
                'lqh_total_sync',
                'lqh_account_id',
                'lqh_account_title',
            ]),
            1
        );
    }
}
