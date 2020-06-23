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

/**
 * Common configurations of the framework
 *
 * @author Prince Dorcis <princedorcis@gmail.com>
 */
class Config
{
    protected $config = [];

    public function __construct()
    {
        $this->setAll([
            'menus_root_path' => realpath(__DIR__ . '/../../../../app/Menus/'),
            'config_root_path' => realpath(__DIR__ . '/../../../../config/'),
            'storage_root_path' => realpath(__DIR__ . '/../../../../storage/logs/'),
            'logs_root_path' => realpath(__DIR__ . '/../../../../storage/logs/'),
            'sessions_root_path' => realpath(__DIR__ . '/../../../../storage/sessions/'),
            'app_config_path' => realpath(__DIR__ . '/../../../../config/app.php'),
            'database_config_path' => realpath(__DIR__ . '/../../../../config/database.php'),
            'session_config_path' => realpath(__DIR__ . '/../../../../config/session.php'),
            'default_env' => realpath(__DIR__ . '/../../../../.env'),
            'default_namespace' => 'Prinx\Rejoice\\',
        ]);
    }

    public function get($name, $default = null)
    {
        if ($this->has($name)) {
            return $this->config[$name];
        } elseif ($default) {
            return $default;
        } else {
            throw new \Exception('Undefined key ' . $name . ' in the framework configuration');
        }
    }

    public function has($name)
    {
        return isset($this->config[$name]);
    }

    public function set($name, $value)
    {
        $this->config[$name] = $value;
    }

    public function setAll($config)
    {
        $this->config = $config;
    }
}
