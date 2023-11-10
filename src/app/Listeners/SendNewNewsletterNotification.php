<?php

namespace App\Listeners;

use App\Events\NewsletterRegistered;
use App\Mail\AlertNotification;
use App\Models\User;
use App\Notifications\NewsletterNotification;
use App\Traits\SendMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendNewNewsletterNotification implements ShouldQueue
{
    use SendMail;

    /**
     * Handle the event.
     *
     * @param  NewsletterRegistered  $event
     *
     * @return void
     */
    public function handle(NewsletterRegistered $event)
    {
        debug('Listeners for event NewsletterRegistered');

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new NewsletterNotification([
            'email' => $event->email(),
            'message' => 'Nova newsletter cadastrada: ' . $event->email(),
        ]));
        $this->sendMail(env('MAIL_ROOT'), new AlertNotification(
            'Nova Newsletter'
        ));
    }
}
