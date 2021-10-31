<?php

namespace App\Menus\Emergency;

use App\Menus\Menu;

class ContactNumber extends Menu
{
    public function message()
    {
        return [
            'Parish Registration',
            '',
            'Enter your Emergency Contact Number',
            '',
        ];
    }

    public function defaultNextMenu()
    {
        return 'Emergency::RelationshipType';
    }
}
