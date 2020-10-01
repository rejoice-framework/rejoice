<?php

use function Prinx\Dotenv\env;

return [

    /*
     * Supported session drivers are "file" and "database"
     */
    'driver' => env('SESSION_DRIVER', 'file'),

    /*
     * Use to control the timeout of the final response.
     * The default is 180 seconds
     */
    'timeout' => 180,

    /*
     * Remaining session will be deleted after the lifetime has passed
     * The default is 18.000 seconds (5 hours)
     */
    'lifetime' => 60 * 60 * 5,

    /*
     * Session database configuration
     */
    'database' => [
        'user'     => env('SESSION_DB_USER', ''),
        'password' => env('SESSION_DB_PASS', ''),
        'host'     => env('SESSION_DB_HOST', ''),
        'port'     => env('SESSION_DB_PORT', ''),
        'dbname'   => env('SESSION_DB_NAME', ''),
    ],

];
