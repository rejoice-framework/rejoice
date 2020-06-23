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
 * Framework's base validator
 *
 * @author Prince Dorcis <princedorcis@gmail.com>
 */
class Validator
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function validateStringParam(
        $param,
        $paramName,
        $pattern = '/[a-z][a-z0-9]+/i',
        $maxLength = 126,
        $minLength = 1
    ) {
        if (!is_string($param)) {
            exit('The parameter "' . $paramName . '" must be a string.');
        }

        if (strlen($param) < $minLength) {
            exit('The parameter "' . $paramName . '" is too short. It must be at least ' . $minLength . ' character(s).');
        }

        if (strlen($param) > $maxLength) {
            exit('The parameter "' . $paramName . '" is too long. It must be at most ' . $maxLength . ' characters.');
        }

        if (!preg_match($pattern, $param) === 1) {
            exit('The parameter "' . $paramName . '" contains unexpected character(s).');
        }

        return true;
    }
}
