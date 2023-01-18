<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class AddressTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function addresses_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('addresses', [
                'adr_id', 'user_id', 'adr_name', 'adr_surname', 'adr_phone',
                'adr_zipcode', 'adr_address', 'adr_number', 'adr_district',
                'adr_city', 'adr_complement', 'adr_type', 'adr_uf',
            ]),
            1
        );
    }
}
