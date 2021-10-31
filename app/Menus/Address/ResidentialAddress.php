<?php

namespace App\Menus\Address;

use App\Menus\Menu;

class ResidentialAddress extends Menu
{
    public function message()
    {
        return [
            'Parish Registration',
            '',
            'Enter your Residential Address',
            '',
        ];
    }

    public function defaultNextMenu()
    {
        return 'Address::ResidentialStatus';
    }
}
