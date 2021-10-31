<?php

namespace App\Menus\Address;

use App\Menus\Menu;

class DigitalAddress extends Menu
{
    public function message()
    {
        return [
            'Parish Registration',
            '',
            'Enter your Digital Address (Optional)',
            '',
        ];
    }

    public function defaultNextMenu()
    {
        return 'Address::ResidentialAddress';
    }

    public function actions()
    {
        return $this->withBack([
            '1'=> [
                'display'  => 'Next',
                'next_menu'=> 'Address::ResidentialAddress',
            ],
        ]);
    }
}
