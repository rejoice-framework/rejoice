<?php

namespace App\Menus\Emergency;

use App\Menus\Menu;

class ContactName extends Menu
{
    public function message()
    {
        return [
            'Parish Registration',
            '',
            'Enter your Emergency Contact Name',
            '',
        ];
    }

    public function defaultNextMenu()
    {
        return 'Emergency::ContactNumber';
    }
}
