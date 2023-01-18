<?php

namespace App\Listeners;

use App\Events\ProductCommentRegistered;
use App\Mail\AlertNotification;
use App\Models\User;
use App\Notifications\ProductCommentNotification;
use App\Traits\SendMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendNewProductCommentNotification implements ShouldQueue
{
    use SendMail;

    /**
     * Handle the event.
     *
     * @param  ProductCommentRegistered  $event
     *
     * @return void
     */
    public function handle(ProductCommentRegistered $event)
    {
        $admins = User::where('role', 'admin')->get();
        $product = $event->product();
        Notification::send($admins, new ProductCommentNotification([
            'product' => $product->pro_sku,
            'message' => 'Nova mensagem no produto: ' . $product->pro_sku,
        ]));
        $this->sendMail(env('MAIL_ROOT'), new AlertNotification(
            'Nova mensagem no produto'
        ));
    }
}
