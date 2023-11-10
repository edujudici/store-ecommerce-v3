<?php

namespace App\Listeners;

use App\Events\OrderPaidRegistered;
use App\Mail\AlertNotification;
use App\Models\User;
use App\Notifications\OrderPaidNotification;
use App\Traits\SendMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendNewOrderPaidNotification implements ShouldQueue
{
    use SendMail;

    /**
     * Handle the event.
     *
     * @param  OrderPaidRegistered  $event
     *
     * @return void
     */
    public function handle(OrderPaidRegistered $event)
    {
        debug('Listeners for event OrderPaidRegistered');

        $admins = User::where('role', 'admin')->get();
        $order = $event->order();
        Notification::send($admins, new OrderPaidNotification([
            'order' => $order->ord_protocol,
            'message' => 'Pedido atualizado para pago: ' . $order->ord_protocol,
        ]));
        $this->sendMail(env('MAIL_ROOT'), new AlertNotification(
            'Pagamento recebido'
        ));
    }
}
