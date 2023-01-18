<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class FeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function features_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('features', [
                'fea_id', 'fea_title', 'fea_description', 'fea_image',
            ]),
            1
        );
    }
}
