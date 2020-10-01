<?php

/**
 * Bootstraps the application.
 *
 * @author Prince Dorcis <princedorcis@gmail.com>
 */
require_once __DIR__.'/../vendor/autoload.php';
include_once __DIR__.'/../app/constants.php';

$application = new Rejoice\Foundation\App;
$application->run();
