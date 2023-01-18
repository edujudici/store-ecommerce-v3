<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class BrandTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function brands_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('brands', [
                'bra_id', 'bra_title', 'bra_image',
            ]),
            1
        );
    }
}
