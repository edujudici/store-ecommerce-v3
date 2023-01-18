<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class FaqTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function faqs_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('faqs', [
                'faq_id', 'faq_title', 'faq_description',
            ]),
            1
        );
    }
}
