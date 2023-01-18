<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class BannerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function banners_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('banners', [
                'ban_id', 'ban_title', 'ban_description', 'ban_image',
                'ban_url',
            ]),
            1
        );
    }
}
