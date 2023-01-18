<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class MercadoLivreUserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function mercadolivre_users_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('mercadolivre_users', [
                'meu_id', 'meu_user_id', 'meu_nickname',
                'meu_registration_date', 'meu_address_city',
                'meu_address_state', 'meu_points', 'meu_permalink',
                'meu_level_id', 'meu_power_seller_status',
                'meu_transactions_canceled', 'meu_transactions_completed',
                'meu_transactions_total',
            ]),
            1
        );
    }
}
