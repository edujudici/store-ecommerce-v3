<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class CompanyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function company_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('company', [
                'com_id', 'com_title', 'com_description', 'com_image',
                'com_phone', 'com_work_hours', 'com_mail', 'com_iframe',
                'com_address', 'com_zipcode', 'com_number', 'com_district',
                'com_city', 'com_complement', 'com_uf'
            ]),
            1
        );
    }
}
