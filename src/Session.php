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

require_once 'constants.php';

/**
 * Handle the USSD Session: save and retrieve the session data from the database
 *
 * @author Prince Dorcis <princedorcis@gmail.com>
 */
class Session
{
    protected $driver;
    protected $app;

    protected $id;
    protected $msisdn;
    public $data = [];

    public function __construct($app)
    {
        $this->app = $app;
        $this->id = $app->sessionId();
        $this->msisdn = $app->msisdn();

        $this->driver = (require realpath(__DIR__ . '/../../../../config/session.php'))['driver'];
    }

    public function isPrevious()
    {
        return !empty($this->data);
    }

    protected function start()
    {
        switch ($this->app->ussdRequestType()) {
            case APP_REQUEST_INIT:
                if ($this->app->params('always_start_new_session')) {
                    $this->deletePreviousData();
                    $this->data = [];
                } else {
                    $this->data = $this->retrievePreviousData();
                }

                break;

            case APP_REQUEST_USER_SENT_RESPONSE:
                $this->data = $this->retrievePreviousData();
                // var_dump($this->data);
                break;
        }
    }

    protected function deletePreviousData()
    {
        $this->delete();
    }

    public function delete()
    {}

    public function retrievePreviousData()
    {}

    public function data()
    {
        return $this->data;
    }

    public function get($key = null, $default = null)
    {
        if (!$key) {
            $this->data[DEVELOPER_SAVED_DATA] = $this->data[DEVELOPER_SAVED_DATA] ?? [];
            return $this->data[DEVELOPER_SAVED_DATA];
        }

        if (isset($this->data[DEVELOPER_SAVED_DATA][$key])) {
            return $this->data[DEVELOPER_SAVED_DATA][$key];
        }

        if (\func_num_args() > 1) {
            return $default;
        }

        throw new \Exception('Index "' . $key . '" not found in the session data.');
    }

    public function set($key, $value)
    {
        if (!$this->hasMetadata(DEVELOPER_SAVED_DATA)) {
            $this->setMetadata(DEVELOPER_SAVED_DATA, []);
        }

        $this->data[DEVELOPER_SAVED_DATA][$key] = $value;
    }

    public function remove($key)
    {
        if (isset($this->data[DEVELOPER_SAVED_DATA][$key])) {
            unset($this->data[DEVELOPER_SAVED_DATA][$key]);
        }
    }

    public function has($key)
    {
        return isset($this->data[DEVELOPER_SAVED_DATA][$key]);
    }

    public function hasMetadata($key)
    {
        return isset($this->data[$key]);
    }

    public function setMetadata($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function removeMetadata($key)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        }
    }

    public function metadata($key = null, $default = null)
    {
        if (!$key) {
            return $this->data;
        }

        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        if (\func_num_args() > 1) {
            return $default;
        }

        throw new \Exception('Index "' . $key . '" not found in the session.');
    }

    public function reset()
    {
        $this->data = [];
    }
}
