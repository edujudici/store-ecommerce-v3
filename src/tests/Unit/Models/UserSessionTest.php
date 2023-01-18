<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class UserSessionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function users_session_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('users_session', [
                'uss_id', 'user_id', 'uss_type', 'uss_json',
            ]),
            1
        );
    }
}
