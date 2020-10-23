<?php

use function Prinx\Dotenv\env;

return [

    /*
     * file|database
     */
    'driver' => env('SESSION_DRIVER', 'file'),

    /*
     * Timeout of the final response
     */
    'timeout'  => 5, // 3min

    /*
     * Sessions will be deleted after the lifetime has passed
     * Default = 60 * 60 * 5 = 5hours
     */
    'lifetime' => 60 * 60 * 5,

    'database' => [
        'user'     => env('SESSION_DB_USER', ''),
        'password' => env('SESSION_DB_PASS', ''),
        'host'     => env('SESSION_DB_HOST', ''),
        'port'     => env('SESSION_DB_PORT', ''),
        'dbname'   => env('SESSION_DB_NAME', ''),
    ],

];
