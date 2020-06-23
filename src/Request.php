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

use Prinx\Utils\Str;

/**
 * Handle the request to the framework
 *
 * @author Prince Dorcis <princedorcis@gmail.com>
 */
class Request
{
    protected $query = [];

    protected $input = [
        'msisdn' => '',
        'ussdString' => '',
        'ussdServiceOp' => '',
        'network' => '',
        'channel' => 'USSD',
    ];

    public function __construct()
    {
        $this->hydrate($_POST);
    }

    public function hydrate($requestParams)
    {
        $input = [];
        foreach (ALLOWED_REQUEST_PARAMS as $param) {
            $input[$param] = $this->sanitize($requestParams[$param]);
        }

        if (isset($input['msisdn'])) {
            $input['msisdn'] = Str::internationaliseNumber($input['msisdn']);
        }

        if (isset($requestParams['channel'])) {
            $input['channel'] = strtoupper($this->sanitize($requestParams['channel']));
        }

        $this->input = array_merge($this->input, $input);
    }

    public function input($key = null, $default = null)
    {
        return $this->param($this->input, $key, $default);
    }

    public function query($key = null, $default = null)
    {
        return $this->param($this->query, $key, $default);
    }

    public function param($param, $key, $default)
    {
        if (!$key) {
            return $param;
        }

        if (!isset($param[$key])) {
            throw new \Exception('Undefined request input `' . $key . '`');
        }

        return $key ? $param[$key] : $default;
    }

    public function forceInput($name, $value)
    {
        $this->input[$name] = $value;
    }

    public function sanitize($var)
    {
        // return htmlspecialchars(addslashes(urldecode($var)));
        return htmlspecialchars(urldecode($var));
    }
}
