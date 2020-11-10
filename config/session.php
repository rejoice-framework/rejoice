<?php

use function Prinx\Dotenv\env;

return [

    /*
     * file|database
     */
    'driver' => env('SESSION_DRIVER', 'file'),

    /*
     * Timeout of the final response
     * Default = 3 mins
     */
    'timeout' => 180,

    /*
     * Sessions will be deleted after the lifetime has passed
     * Default = 5 hours
     */
    'lifetime' => 18000,

    'database' => [
        'user'     => env('SESSION_DB_USER', ''),
        'password' => env('SESSION_DB_PASS', ''),
        'host'     => env('SESSION_DB_HOST', ''),
        'port'     => env('SESSION_DB_PORT', ''),
        'dbname'   => env('SESSION_DB_NAME', ''),
    ],

];
