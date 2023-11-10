<?php

namespace App\Listeners;

use App\Events\OrderCommentAnswerRegistered;
use App\Mail\AnswerOrder;
use App\Notifications\OrderCommentNotification;
use App\Traits\SendMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendNewOrderCommentAnswerNotification implements ShouldQueue
{
    use SendMail;

    /**
     * Handle the event.
     *
     * @param  OrderCommentAnswerRegistered  $event
     *
     * @return void
     */
    public function handle(OrderCommentAnswerRegistered $event)
    {
        debug('Listeners for event OrderCommentAnswerRegistered');

        $order = $event->order();
        Notification::send($order->user, new OrderCommentNotification([
            'order' => $order->ord_protocol,
            'message' => 'Nova mensagem no pedido: ' . $order->ord_protocol,
        ]));
        $this->sendMail($order->user->email, new AnswerOrder([
            'name' => $order->user->name,
            'protocol' => $order->ord_protocol,
        ]));
    }
}
