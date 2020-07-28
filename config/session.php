<?php
use function Prinx\Dotenv\env;

return [

    'driver' => env('SESSION_DRIVER', 'file'), // file|database

    // Use to control the timeout of the final response
    'timeout' => 180, // 3min

    // Remaining session will be deleted after the lifetime has passed
    'lifetime' => 60 * 60 * 5, // 5h

    'database' => [
        'user' => env('SESSION_DB_USER', ''),
        'password' => env('SESSION_DB_PASS', ''),
        'host' => env('SESSION_DB_HOST', ''),
        'port' => env('SESSION_DB_PORT', ''),
        'dbname' => env('SESSION_DB_NAME', ''),
    ],

];
