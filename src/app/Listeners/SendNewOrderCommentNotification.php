<?php

namespace App\Listeners;

use App\Events\OrderCommentRegistered;
use App\Mail\AlertNotification;
use App\Models\User;
use App\Notifications\OrderCommentNotification;
use App\Traits\SendMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendNewOrderCommentNotification implements ShouldQueue
{
    use SendMail;

    /**
     * Handle the event.
     *
     * @param  OrderCommentRegistered  $event
     *
     * @return void
     */
    public function handle(OrderCommentRegistered $event)
    {
        debug('Listeners for event OrderCommentRegistered');

        $order = $event->order();
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new OrderCommentNotification([
            'order' => $order->ord_protocol,
            'message' => 'Nova mensagem no pedido: ' . $order->ord_protocol,
        ]));
        $this->sendMail(env('MAIL_ROOT'), new AlertNotification(
            'Nova mensagem no pedido'
        ));
    }
}
