<?php

use function Prinx\Dotenv\env;

return [

    'sender' => [
        'from' => env('MAIL_SENDER_FROM', ''),
        'name' => env('MAIL_SENDER_NAME', ''),
    ],

    'smtp' => [
        'user' => env('MAIL_USER', ''),
        'pass' => env('MAIL_PASS', ''),
        'host' => env('MAIL_HOST', ''),
        'port' => env('MAIL_PORT', 587),
    ],

];
