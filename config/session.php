<?php
use function Prinx\Dotenv\env;

return [
    'driver' => env('SESSION_DRIVER', 'file'), // file|database

    // If saving the sessions in database
    'database' => [
        'user' => env('SESSION_DB_USER', ''),
        'password' => env('SESSION_DB_PASS', ''),
        'host' => env('SESSION_DB_HOST', ''),
        'port' => env('SESSION_DB_PORT', ''),
        'dbname' => env('SESSION_DB_NAME', ''),
    ],
];
