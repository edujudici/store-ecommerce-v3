<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\OrderComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * @group ModelTest
 */
class OrderCommentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp() :void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->order = Order::factory()
            ->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function orders_comment_database_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('orders_comment', [
                'orc_id', 'ord_id', 'orc_name', 'orc_question', 'orc_answer',
                'orc_answer_date', 'orc_image',
            ]),
            1
        );
    }

    /** @test */
    public function a_order_comment_belongs_to_a_order()
    {
        $orderComment = OrderComment::factory()
            ->create(['ord_id' => $this->order->ord_id]);

        $this->assertInstanceOf(Order::class, $orderComment->order);
    }
}
