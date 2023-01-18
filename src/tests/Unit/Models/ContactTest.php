<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class ContactTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function contacts_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('contacts', [
                'con_id', 'con_name', 'con_email', 'con_subject', 'con_message',
                'con_answer',
            ]),
            1
        );
    }
}
