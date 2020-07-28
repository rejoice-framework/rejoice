<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Prinx\Arr;

/**
 * @todo The tests need works
 */
class EnvTest extends TestCase
{
    public function testExample()
    {
        $config = $this->loadConfig();

        $this->assertEquals(Arr::multiKeyGet('app.paginate_forward_display', $config), 'More');
        $this->assertEquals(Arr::multiKeyGet('app.paginate_forward_display', $config), 'More');
        $this->assertTrue(1 === 1);
    }

    public function testParseEnv()
    {
        $this->env = $this->parseEnv();
        $this->assertTrue('dev' === $this->env['APP_ENV']);
    }

    public function parseEnv()
    {

    }

    public function loadConfig()
    {
        $directory = new \DirectoryIterator(realpath(__DIR__ . '/../../config/'));

        $iterator = new \IteratorIterator($directory);
        $files = [];

        foreach ($iterator as $info) {
            $filename = $info->getFileName();

            if ('.' === $filename || '..' === $filename) {
                continue;
            }

            $name = substr($filename, 0, strlen($filename) - strlen('.php'));

            $files[$name] = require $info->getPathname();
        }

        return $files;
    }
}
