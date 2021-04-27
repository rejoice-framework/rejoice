<?php

use function Prinx\Dotenv\env;

return [
    /*
     * file|database
     */
    'driver' => env('SESSION_DRIVER', 'file'),

    /*
     * Timeout of the final response
     * Default 180 (3 minutes)
     */
    'timeout' => 180,

    /*
     * The session is invalidated after the lifetime has passed and
     * the user will be obliged to restart from the welcome menu.
     * Default 18000 (5h, in seconds)
     */
    'lifetime' => 18000,

    'database' => [
        'user' => env('SESSION_DB_USER', ''),
        'password' => env('SESSION_DB_PASS', ''),
        'host' => env('SESSION_DB_HOST', ''),
        'port' => env('SESSION_DB_PORT', ''),
        'dbname' => env('SESSION_DB_NAME', ''),
    ],
];
