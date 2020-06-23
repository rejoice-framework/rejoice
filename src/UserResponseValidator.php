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

use Prinx\Utils\Date;
use Prinx\Utils\Str;

/**
 * Defines the methods to easily validate the user's response.
 *
 * @author Prince Dorcis <princedorcis@gmail.com>
 */
class UserResponseValidator
{

    public static function validate($response, $validation_rules)
    {
        $validation = new \stdClass;
        $validation->validated = true;

        $rules = $validation_rules;
        if (!is_array($rules)) {
            $rules = explode('|', $rules);
        }

        foreach ($rules as $rule) {
            $exploded_rule = explode(':', $rule);

            $method = 'is' . Str::pascalCase($exploded_rule[0]);

            if (method_exists(self::class, $method)) {
                $arguments = isset($exploded_rule[1]) ? explode(',', $exploded_rule[1]) : [];

                $specific = call_user_func([self::class, $method], $response, ...$arguments);

                if (!$specific->validated) {
                    $validation->validated = false;
                    $validation->error = $specific->error;
                    break;
                }
            } else {
                throw new \Exception('Unknown validation rule `' . $exploded_rule[0] . '`');
            }
        }

        return $validation;
    }

    public static function isMin($num, $min)
    {
        $v = new \stdClass;
        $v->validated = true;

        if (!(floatval($num) >= floatval($min))) {
            $v->validated = false;
            $v->error = 'The response must be less than ' . $min;
        }

        return $v;
    }

    public static function isMax($num, $max)
    {
        $v = new \stdClass;
        $v->validated = true;

        if (!(floatval($num) <= floatval($max))) {
            $v->validated = false;
            $v->error = 'The response must be greater than ' . $max;
        }

        return $v;
    }

    public static function isMinLength($str, $minLen)
    {
        $v = new \stdClass;
        $v->validated = true;

        if (!Str::isMinLength($str, intval($minLen))) {
            $v->validated = false;
            $v->error = 'At least ' . $minLen . ' characters';
        }

        return $v;
    }

    public static function isMaxLength($str, $maxLen)
    {
        $v = new \stdClass;
        $v->validated = true;

        if (!Str::isMaxLength($str, intval($maxLen))) {
            $v->validated = false;
            $v->error = 'At most ' . $maxLen . ' characters';
        }

        return $v;
    }

    public static function isMinLen($str, $num)
    {
        return self::isMinLength($str, $num);
    }

    public static function isMaxLen($str, $num)
    {
        return self::isMaxLength($str, $num);
    }

    public static function isAlpha($str)
    {
        $v = new \stdClass;
        $v->validated = true;

        if (!Str::isAlphabetic($str)) {
            $v->validated = false;
            $v->error = 'Invalid character in the response';
        }

        return $v;
    }

    public static function isAlphaNum($str)
    {
        $v = new \stdClass;
        $v->validated = true;

        if (!Str::isAlphabetic($str)) {
            $v->validated = false;
            $v->error = 'Invalid character in the response';
        }

        return $v;
    }

    public static function isNumeric($str)
    {
        $v = new \stdClass;
        $v->validated = true;

        if (!Str::isNumeric($str)) {
            $v->validated = false;
            $v->error = 'Invalid response';
        }

        return $v;
    }

    public static function isInteger($str)
    {
        $v = new \stdClass;
        $v->validated = true;

        if (!Str::isNumeric($str)) {
            $v->validated = false;
            $v->error = 'Invalid response';
        }

        return $v;
    }

    public static function isFloat($str)
    {
        $v = new \stdClass;
        $v->validated = true;

        if (!Str::isFloatNumeric($str)) {
            $v->validated = false;
            $v->error = 'Invalid response';
        }

        return $v;
    }

    public static function isAmount($str)
    {
        return self::isFloat($str);
    }

    public static function isTel($str)
    {
        $v = new \stdClass;
        $v->validated = true;

        if (!Str::isTelNumber($str)) {
            $v->validated = false;
            $v->error = 'Invalid phone number.';
        }

        return $v;
    }

    public static function isRegex($str, $pattern)
    {
        $v = new \stdClass;
        $v->validated = true;

        $matched = preg_match($pattern, $str);
        if ($matched === 0) {
            $v->validated = false;
            $v->error = 'The response does not match the pattern.';
        } elseif ($matched === false) {
            throw new \Exception("Error in the validation regex: " . $pattern);
        }

        return $v;
    }

    public static function isDate($date, $format = 'd/m/Y')
    {
        $v = new \stdClass;
        $v->validated = true;

        if (!Date::isDate($date, $format)) {
            $v->validated = false;
            $v->error = 'Invalid date.';
        }

        return $v;
    }

    public static function isAge($str)
    {
        return self::validate($str, 'numeric|min:0|max:100');
    }

    public static function isName($str)
    {
        return self::validate($str, 'alpha|min_len:3|max_len:50');
    }
}
