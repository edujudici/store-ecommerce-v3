<?php

namespace App\Listeners;

use App\Events\OrderStatusRegistered;
use App\Mail\OrderStatusNotification;
use App\Notifications\OrderNotification;
use App\Traits\SendMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendNewOrderStatusNotification implements ShouldQueue
{
    use SendMail;

    /**
     * Handle the event.
     *
     * @param  OrderStatusRegistered  $event
     *
     * @return void
     */
    public function handle(OrderStatusRegistered $event)
    {
        $order = $event->order();
        $status = $event->status();
        Notification::send($order->user, new OrderNotification([
            'order' => $order->ord_protocol,
            'message' => 'Pedido ' . $order->ord_protocol .
                " atualizado para: {$status}",
        ]));
        $this->sendMail($order->user->email, new OrderStatusNotification([
            'name' => $order->user->name,
            'protocol' => $order->ord_protocol,
            'status' => $status,
        ]));
    }
}
