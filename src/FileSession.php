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
require_once 'Session.php';
require_once 'SessionInterface.php';
// use Session;
// use SessionInterface;

/**
 * Handles file session storage
 *
 * @author Prince Dorcis <princedorcis@gmail.com>
 */
class FileSession extends Session implements SessionInterface
{
    protected $storage = '/../../../../storage/sessions/';
    protected $file;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->id = trim($this->msisdn, '+');
        $this->file = realpath(__DIR__ . $this->storage) . '/' . $this->id;
        $this->start();
    }

    public function delete()
    {
        unlink($this->file);
    }

    public function hardReset()
    {
        file_put_contents($this->file, '{}');
    }

    public function retrievePreviousData()
    {
        $this->data = $this->retrieveData();

        if (!empty($this->data)) {
            // $this->delete();
            $this->data['id'] = $this->app->sessionId();
            $this->save();
        }

        return $this->data;
    }

    public function retrieveData()
    {
        if (!file_exists($this->file)) {
            return [];
        }

        $jsonData = file_get_contents($this->file);
        $data = ($jsonData !== '') ?
        json_decode($jsonData, true) : [];

        return $data;
    }

    public function previousSessionNotExists()
    {
        if (file_exists($this->file)) {
            $data = file_get_contents($this->file);

            return empty(json_decode($data, true));
        }

        return false;
    }

    public function save()
    {
        return file_put_contents($this->file, json_encode($this->data));
    }

    public function file()
    {
        return $this->file;
    }
}
