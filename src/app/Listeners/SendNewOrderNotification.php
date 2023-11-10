<?php

namespace App\Listeners;

use App\Events\OrderRegistered;
use App\Mail\AlertNotification;
use App\Models\User;
use App\Notifications\OrderNotification;
use App\Traits\SendMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendNewOrderNotification implements ShouldQueue
{
    use SendMail;

    /**
     * Handle the event.
     *
     * @param  OrderRegistered  $event
     *
     * @return void
     */
    public function handle(OrderRegistered $event)
    {
        debug('Listeners for event OrderRegistered');

        $admins = User::where('role', 'admin')->get();
        $order = $event->order();
        Notification::send($admins, new OrderNotification([
            'order' => $order->ord_protocol,
            'message' => 'Novo pedido realizado: ' . $order->ord_protocol,
        ]));
        $this->sendMail(env('MAIL_ROOT'), new AlertNotification(
            'Novo pedido'
        ));
    }
}
