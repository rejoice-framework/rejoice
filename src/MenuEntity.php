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

use App\Helpers\Log;
use Prinx\Utils\Str;

/**
 * Provides shortcuts to app methods and properties for the user App
 *
 * @author Prince Dorcis <princedorcis@gmail.com>
 */
class MenuEntity
{
    protected $app;
    protected $name;
    protected $logger;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function respond($msg)
    {
        $this->softEnd($msg);
    }

    public function respondAndContinue($msg)
    {
        $this->respond($msg);
    }

    public function respondAndExit($msg)
    {
        $this->hardEnd($msg);
    }

    public function tel()
    {
        return $this->msisdn();
    }

    public function log($msg)
    {
        $this->logger()->info($msg);
    }

    public function logger()
    {
        if (!$this->logger) {
            $dir = $this->app->config('logs_root_path') . '/' . $this->name;
            if (!is_dir($dir)) {
                mkdir($dir);
            }

            $file = $dir . '/' . date('Y-m-d') . '.log';
            $this->logger = new Log($file);
        }

        return $this->logger;
    }

    public function menuName()
    {
        return $this->name;
    }

    public function insertMainMenuOption($option = '00', $display = 'Main menu')
    {
        $this->insertMenuActions($this->mainMenuOption($option, $display));
    }

    public function mainMenuOption($option = '00', $display = 'Main menu')
    {
        return [
            $option => [
                ITEM_MSG => $display,
                ITEM_ACTION => APP_WELCOME,
            ],
        ];
    }

    public function insertBackOption($option = '0', $display = 'Back')
    {
        $this->insertMenuActions($this->backOption($option, $display));
    }

    public function backOption($option = '0', $display = 'Back')
    {
        return [
            $option => [
                ITEM_MSG => $display,
                ITEM_ACTION => APP_BACK,
            ],
        ];
    }

    public function insertPaginateBackOption($option = '0', $display = 'Back')
    {
        $this->insertMenuActions($this->paginateBackOption($option, $display));
    }

    public function paginateBackOption($option = '0', $display = 'Back')
    {
        return [
            $option => [
                ITEM_MSG => $display,
                ITEM_ACTION => APP_PAGINATE_BACK,
            ],
        ];
    }

    public function insertPaginateForwardOption($option, $display = 'Show more')
    {
        $this->insertMenuActions($this->paginateForwardOption($option, $display));
    }

    public function paginateForwardOption($option, $display = 'Show more')
    {
        return [
            $option => [
                ITEM_MSG => $display,
                ITEM_ACTION => APP_PAGINATE_FORWARD,
            ],
        ];
    }

    public function currentMenuName()
    {
        return $this->menuName();
    }

    public function validName($name, $error = 'Invalid name')
    {
        if (!Str::isAlphabetic($name, 2)) {
            $this->app->setError($error);
            return false;
        }

        return true;
    }

    public function setError($error = '')
    {
        $error .= $this->app->error() ? "\n" : '';
        $this->app->setError($error);
    }

    public function sendSmsAndExit($sms)
    {
        $this->app->sendSms($sms);
        exit;
    }

    public function softEnd($msg)
    {
        if ($this->app->isUssdChannel() &&
            !$this->app->params('allow_timeout') &&
            $this->app->params('cancel_msg')
        ) {
            $temp = $msg . "\n\n" . $this->app->params('cancel_msg');

            $msg = $this->app->contentOverflows($temp) ? $msg : $temp;
        }

        return $this->response()->softEnd($msg);
    }

    public function hardEnd()
    {
        return $this->response()->hardEnd();
    }

    public function setApp(Kernel $app)
    {
        $this->app = $app;
    }

    public function emptyMenuActions($menuName = '')
    {
        $menuName = $menuName ?: $this->currentMenuName();
        $this->app->emptyActionsOfMenu($menuName);
    }

    public function setMenuActions($actions, $menuName = '')
    {
        $menuName = $menuName ?: $this->currentMenuName();
        $this->app->setMenuActions($actions, $menuName);
    }

    public function response()
    {
        return $this->app->response();
    }

    public function __call($method, $args)
    {
        if (method_exists($this->app, $method)) {
            return call_user_func([$this->app, $method], ...$args);
        }

        throw new \Exception('Undefined method `' . $method . '` in class ' . get_class($this));
    }
}
