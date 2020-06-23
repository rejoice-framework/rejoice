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
 * Create an instance instance of the application
 *
 * @author Prince Dorcis <princedorcis@gmail.com>
 */

class App
{
    public static function run($appName = 'default')
    {
        $app = new Kernel($appName);
        return $app->run();
    }
}
