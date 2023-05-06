<?php

namespace Tests\Unit\Models;

use App\Models\LoadQuestionHistory;
use App\Models\MercadoLivre;
use App\Models\MercadoLivreComment;
use App\Models\MercadoLivreNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class MercadoLivreTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $mercadoLivre;

    public function setUp() :void
    {
        parent::setUp();

        $this->mercadoLivre = MercadoLivre::factory()->create();
    }

    /** @test */
    public function mercadolivre_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('mercadolivre', [
                'mel_id', 'mel_title', 'mel_code_tg', 'mel_access_token',
                'mel_token_type', 'mel_expires_in', 'mel_scope', 'mel_user_id',
                'mel_refresh_token', 'mel_after_sales_message',
                'mel_after_sales_enabled',
            ]),
            1
        );
    }

    /** @test */
    public function a_mercadolivre_has_many_mercadolivre_comments()
    {
        MercadoLivreComment::factory()
            ->count(3)
            ->for($this->mercadoLivre)
            ->create();

        $this->assertCount(3, $this->mercadoLivre->comments);
        $this->assertInstanceOf(Collection::class, $this->mercadoLivre->comments);
    }

    /** @test */
    public function a_mercadolivre_has_many_mercadolivre_notifications()
    {
        MercadoLivreNotification::factory()
            ->count(3)
            ->for($this->mercadoLivre)
            ->create();

        $this->assertCount(3, $this->mercadoLivre->notifications);
        $this->assertInstanceOf(Collection::class, $this->mercadoLivre->notifications);
    }

    /** @test */
    public function a_mercadolivre_has_many_mercadolivre_histories()
    {
        LoadQuestionHistory::factory()
            ->count(3)
            ->for($this->mercadoLivre)
            ->create();

        $this->assertCount(3, $this->mercadoLivre->histories);
        $this->assertInstanceOf(Collection::class, $this->mercadoLivre->histories);
    }
}
