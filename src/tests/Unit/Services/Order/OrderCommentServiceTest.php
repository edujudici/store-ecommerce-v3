<?php

namespace Tests\Unit\Services\Order;

use App\Models\Order;
use App\Models\OrderComment;
use App\Models\User;
use App\Services\Order\OrderCommentService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @group ServiceTest
 */
class OrderCommentServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new OrderCommentService(
            new Order(),
            new OrderComment()
        );
    }

    /** @test  */
    public function should_list_all_items()
    {
        $order = Order::factory()->create();
        OrderComment::factory()->count(3)
            ->create(['ord_id' => $order->ord_id]);

        $response = $this->service->indexAll();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_list_items()
    {
        $order = Order::factory()->create();
        OrderComment::factory()->count(3)
            ->create(['ord_id' => $order->ord_id]);

        $request = Request::create('/', 'POST', [
            'id' => $order->ord_protocol,
        ]);

        $response = $this->service->index($request);

        $this->assertIsArray($response);
        $this->assertCount(3, $response);
    }

    /** @test  */
    public function should_store_item()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id
        ]);

        $request = Request::create('/', 'POST', [
            'id' => $order->ord_protocol,
            'orc_name' => $this->faker->firstName,
            'orc_question' => $this->faker->title,
            'orc_answer' => $this->faker->title,
            'orc_answer_date' => $this->faker->date(),
        ]);

        $response = $this->service->store($request);

        $this->assertIsArray($response);
        $this->assertCount(1, $response);
    }

    /** @test  */
    public function should_destroy_item()
    {
        $orderComment = OrderComment::factory()->create();

        $request = Request::create('/', 'POST', [
            'id' => $orderComment->orc_id,
        ]);

        $response = $this->service->destroy($request);

        $this->assertTrue($response);
    }

    /** @test  */
    public function should_store_message_order()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id
        ]);

        $this->service->storeMessageOrder($order);

        $this->assertCount(1, $order->comments);
    }

    /** @test  */
    public function should_store_message_welcome()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id
        ]);

        $this->service->storeMessageWelcome($order);

        $this->assertCount(1, $order->comments);
    }

    /** @test  */
    public function should_store_message_finished()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id
        ]);

        $this->service->storeMessageFinished($order);

        $this->assertCount(1, $order->comments);
    }
}
