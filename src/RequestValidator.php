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

require_once 'Validator.php';

use function Prinx\Dotenv\env;

/**
 * Validate the request parameters.
 *
 * @author Prince Dorcis <princedorcis@gmail.com>
 */
class RequestValidator extends Validator
{

    public function validate()
    {
        $this->validateRequestParams();
        $this->validateShortcodeIfRequestInit();
    }

    protected function validateShortcodeIfRequestInit()
    {
        if (
            $this->app->ussdRequestType() === APP_REQUEST_INIT &&
            $this->app->params('validate_shortcode')
        ) {
            $shortcodeCorrect = $this->validateShortcode(
                $this->app->userResponse(),
                env('SHORTCODE', null)
            );

            if (!$shortcodeCorrect) {
                $this->app->addWarningInSimulator(
                    'INVALID SHORTCODE <strong>' . $this->app->userResponse() .
                    '</strong><br/>Use the shortcode defined in the .env file.'
                );

                $this->app->hardEnd('INVALID SHORTCODE');
            }
        }
    }

    public function validateShortcode(
        $sent_shortcode,
        $defined_shortcode
    ) {
        if ($defined_shortcode === null) {
            exit('No "SHORTCODE" value found in the `.env` file. Kindly specify the shortcode application shortcode in the `.env` file.<br><br>Eg.<br>SHORTCODE=*380*75#');
        }

        if ($sent_shortcode !== $defined_shortcode) {
            return false;
        }

        return true;
    }

    public function validateRequestParams()
    {
        $requestParams = $this->app->request()->input();
        if (!is_array($requestParams)) {
            exit('Invalid request parameters received.');
        }

        foreach (ALLOWED_REQUEST_PARAMS as $value) {
            if (!isset($requestParams[$value])) {
                exit("'" . $value . "' is missing in the request parameters.");
            }
        }

        if (
            isset($requestParams['channel']) &&
            !in_array($requestParams['channel'], ALLOWED_REQUEST_CHANNELS)
        ) {
            exit("Invalid parameter 'channel'.");
        }
    }

}
