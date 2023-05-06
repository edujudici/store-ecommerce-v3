<?php

namespace Tests\Unit\Models;

use App\Models\MercadoLivre;
use App\Models\MercadoLivreComment;
use App\Models\MercadoLivreProduct;
use App\Models\MercadoLivreUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class MercadoLivreCommentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $comment;

    public function setUp() :void
    {
        parent::setUp();

        $this->comment = MercadoLivreComment::factory()
            ->for(MercadoLivre::factory())
            ->create();
    }

    /** @test */
    public function mercadolivre_comments_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('mercadolivre_comments', [
                'mec_id', 'mec_date_created', 'mec_item_id', 'mec_seller_id',
                'mec_status', 'mec_text', 'mec_id_secondary',
                'mec_deleted_from_listing', 'mec_hold', 'mec_answer_local',
                'mec_answer_date', 'mec_answer_status', 'mec_answer_text',
                'mec_from_id',
            ]),
            1
        );
    }

    /** @test */
    public function a_mercadolivre_comment_belongs_to_a_mercadolivre()
    {
        $this->assertInstanceOf(MercadoLivre::class, $this->comment->mercadolivre);
    }

    /** @test */
    public function a_mercadolivre_comment_has_a_user()
    {
        MercadoLivreUser::factory()
            ->create(['meu_user_id' => $this->comment->mec_from_id]);

        $this->assertInstanceOf(MercadoLivreUser::class, $this->comment->user);
        $this->assertEquals(1, $this->comment->user->count());
    }

    /** @test */
    public function a_mercadolivre_comment_has_a_product()
    {
        MercadoLivreProduct::factory()
            ->create(['mep_item_id' => $this->comment->mec_item_id]);

        $this->assertInstanceOf(MercadoLivreProduct::class, $this->comment->product);
        $this->assertEquals(1, $this->comment->product->count());
    }
}
