<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class MercadoLivreAnswerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function mercadolivre_answers_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('mercadolivre_answers', [
                'mea_id', 'mea_description',
            ]),
            1
        );
    }
}
