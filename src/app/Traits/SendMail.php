<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;

trait SendMail
{
    /**
     * Helper for send mail
     *
     * @param string $email
     * @param Object $emailData
     *
     * @return void
     */
    protected static function sendMail($email, $emailData): void
    {
        Mail::to($email)->send($emailData);
    }
}
