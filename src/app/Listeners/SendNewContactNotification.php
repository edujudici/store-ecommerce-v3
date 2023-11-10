<?php

namespace App\Listeners;

use App\Events\ContactRegistered;
use App\Mail\AlertNotification;
use App\Models\User;
use App\Notifications\ContactNotification;
use App\Traits\SendMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendNewContactNotification implements ShouldQueue
{
    use SendMail;

    /**
     * Handle the event.
     *
     * @param  ContactRegistered  $event
     *
     * @return void
     */
    public function handle(ContactRegistered $event)
    {
        debug('Listeners for event ContactRegistered');

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new ContactNotification([
            'subject' => $event->subject(),
            'message' => 'Novo contato recebido: ' . $event->subject(),
        ]));
        $this->sendMail(env('MAIL_ROOT'), new AlertNotification(
            'Novo E-mail'
        ));
    }
}
