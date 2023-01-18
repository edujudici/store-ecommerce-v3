<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $order;
    private $status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order, $status)
    {
        $this->order = $order;
        $this->status = $status;
    }

    /**
     * Order
     *
     * @return \App\Models\Order
     */
    public function order(): Order
    {
        return $this->order;
    }

    /**
     * status
     *
     * @return string
     */
    public function status(): string
    {
        return $this->status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
