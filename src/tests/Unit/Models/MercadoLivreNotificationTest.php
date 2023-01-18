<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class MercadoLivreNotificationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function mercadolivre_notifications_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('mercadolivre_notifications', [
                'men_id',
                'men_resource',
                'men_user_id',
                'men_topic',
                'men_application_id',
                'men_attempts',
                'men_sent',
                'men_received',
                'men_send_message',
            ]),
            1
        );
    }
}
