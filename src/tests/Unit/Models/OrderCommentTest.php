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

    private $order;

    public function setUp() :void
    {
        parent::setUp();

        $this->order = Order::factory()->create();
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
            ->for($this->order)
            ->create();

        $this->assertInstanceOf(Order::class, $orderComment->order);
    }
}
