<?php

return [
    // 'application_name' => env('GOOGLE_APPLICATION_NAME'),
    'client_id'        => env('GOOGLE_CLIENT_ID'),
    'client_secret'    => env('GOOGLE_CLIENT_SECRET'),
    'redirect'     => env('GOOGLE_REDIRECT'),
    'scopes'           => [
        \Google\Service\Gmail::MAIL_GOOGLE_COM,
    ],
    'access_type'      => 'offline',
    'approval_prompt'  => 'force',
];
