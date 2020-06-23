<?php

/*
 * This file is part of the Rejoice package.
 *
 * (c) Prince Dorcis <princedorcis@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prinx\Rejoice;

use Prinx\Utils\DB;

/**
 * Connect to and provide the connections to the databases
 *
 * @author Prince Dorcis <princedorcis@gmail.com>
 */
class Database
{
    protected static $sessionDb;
    protected static $appDb = [];

    protected static $defaultDbParams = [
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => '3306',
        'dbname' => '',
        'user' => 'root',
        'password' => '',
    ];

    protected static $sessionConfig =
    __DIR__ . '/../../../../config/session.php';
    protected static $appDbConfig =
    __DIR__ . '/../../../../config/database.php';

    public static function retrieveDbParams($paramsFile)
    {
        $config = [];

        if ((file_exists($paramsFile))) {
            $config = require_once $paramsFile;
        } else {
            throw new \Exception('Database configuration not found. Kindly configure the database settings in the "' . $paramsFile . '"');
        }

        return $config;
    }

    public static function loadSessionDB()
    {
        $config = require_once self::$sessionConfig;

        if ($config['driver'] === 'database') {
            $params = array_merge(self::$defaultDbParams, $config['database']);

            self::$sessionDb = DB::load($params);
            return self::$sessionDb;
        }

        return null;
    }

    public static function loadAppDBs()
    {
        $params = self::retrieveDbParams(self::$appDbConfig);

        foreach ($params as $key => $param) {
            $param = array_merge(self::$defaultDbParams, $param);
            self::$appDb[$key] = DB::load($param);
        }

        return self::$appDb;
    }
}
