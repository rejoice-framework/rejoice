<?php

namespace App\Menus\Customer;

use App\Menus\Menu;

class PostName extends Menu
{
    public function before()
    {
        $this->respond(
            [
                'Once again, Thank you',
            ]
        );
    }
}
