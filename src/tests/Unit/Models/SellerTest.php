<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class SellerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function sellers_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('sellers', [
                'sel_id', 'sel_nickname',
            ]),
            1
        );
    }
}
