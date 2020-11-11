<?php

namespace Tests;

use Illuminate\Database\Capsule\Manager as Capsule;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Rejoice\Foundation\Path;
use function Prinx\Dotenv\loadEnv;

/**
 * Base Test case.
 */
class TestCase extends BaseTestCase
{
    public function __construct()
    {
        parent::__construct();
        loadEnv(Path::toProject('.env'));
        $this->loadDbConnection();
    }

    public function loadDbConnection()
    {
        $capsule = new Capsule;

        $databasConfig = require Path::toConfig('database.php');
        $connections = $databasConfig['connections'];

        if ($defaultConnection = $connections[$databasConfig['default']] ?? []) {
            $capsule->addConnection($defaultConnection);
        }

        foreach ($connections as $name => $config) {
            $capsule->addConnection($config, $name);
        }

        $capsule->bootEloquent();
        $capsule->setAsGlobal();
    }
}
