<?php

namespace App\Menus\RemoteCall;

use App\Menus\Menu;

class GetAuxLocation extends Menu
{
    public $resp1 = [];

    public function before()
    {
        $this->resp1 = get_genders();
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

    public function actions()
    {
        return [
            '1' => [
                'display'   => $this->resp1[0],
                'next_menu' => 'Registration::Occupation',
                'save_as'   => 'gender',
            ],
            '2' => [
                'display'   => $this->resp1[1],
                'next_menu' => 'Registration::Occupation',
                'save_as'   => 'gender',
            ],
        ];
    }
}
