<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class PictureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function pictures_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('pictures', [
                'pic_id', 'pro_sku', 'pic_id_secondary', 'pic_image', 'pic_url',
                'pic_secure_url', 'pic_size', 'pic_max_size', 'pic_quality',
            ]),
            1
        );
    }
}
