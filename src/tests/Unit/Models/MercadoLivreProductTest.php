<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class MercadoLivreProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function mercadolivre_products_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('mercadolivre_products', [
                'mep_id', 'mep_item_id', 'mep_title', 'mep_price',
                'mep_permalink', 'mep_secure_thumbnail',
            ]),
            1
        );
    }
}
