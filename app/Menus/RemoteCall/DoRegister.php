<?php

namespace App\Menus\RemoteCall;

use App\Menus\Menu;

class DoRegister extends Menu
{
    public $resp = [];

    public function before()
    {
        $network = $this->previousResponses('TxtParams::Networks');
        $service = $this->previousResponses('TxtParams::Services');
        $number = $this->previousResponses('TxtParams::Number');
        $amount = $this->previousResponses('TxtParams::Amount');
    }

    public function message()
    {
        return [
            'Parish Registration',
            '',
            'Select Gender',
            '',
        ];
    }
}
