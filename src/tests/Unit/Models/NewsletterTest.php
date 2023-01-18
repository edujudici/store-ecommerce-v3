<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class NewsletterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function newsletters_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('newsletters', [
                'new_id', 'new_email',
            ]),
            1
        );
    }
}
