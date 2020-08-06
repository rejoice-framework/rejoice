<?php

use function Prinx\Dotenv\env;

/*
 * In your menu entity, you can access the database by:
 * $this->db('name')
 *
 * For example:
 *
 * $this->db('name')->query('SELECT name FROM users WHERE age > 18');
 *
 * The `name` is the index that is specified in the array return below.
 * If no name is passed, the default will be returned:
 *
 * $db = $this->db();
 * equivalent to:
 * $db = $this->db('default');
 */

return [

    'default' => [
        'user'     => env('APP_DEFAULT_DB_USER', ''),
        'password' => env('APP_DEFAULT_DB_PASS', ''),
        'host'     => env('APP_DEFAULT_DB_HOST', ''),
        'port'     => env('APP_DEFAULT_DB_PORT', ''),
        'dbname'   => env('APP_DEFAULT_DB_NAME', ''),
    ],

];
