<?php

return [
    'client_id'        => env('API_GOOGLE_CLIENT_ID'),
    'client_secret'    => env('API_GOOGLE_CLIENT_SECRET'),
    'redirect'     => env('API_GOOGLE_REDIRECT'),
    'access_type'      => 'offline',
    'approval_prompt'  => 'force',
];
