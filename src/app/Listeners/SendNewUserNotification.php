<?php

namespace App\Listeners;

use App\Mail\AlertNotification;
use App\Models\User;
use App\Notifications\UserNotification;
use App\Traits\SendMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendNewUserNotification implements ShouldQueue
{
    use SendMail;

    /**
     * Handle the event.
     *
     * @param  object  $event
     *
     * @return void
     */
    public function handle($event)
    {
        debug('Listeners for event Registered');

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new UserNotification([
            'user' => $event->user,
            'message' => 'Novo usuário cadastrado: ' . $event->user->name,
        ]));
        $this->sendMail(env('MAIL_ROOT'), new AlertNotification(
            'Novo usuário'
        ));
    }
}
