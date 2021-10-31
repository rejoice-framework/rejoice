<?php

namespace App\Menus\BioData;

use App\Menus\Menu;

class Surname extends Menu
{
    public function message()
    {
        return [
            'Parish Registration',
            '',
            'Enter your Surname',
        ];
    }

    public function defaultNextMenu()
    {
        return 'BioData::OtherName';
    }

    public function validate($response)
    {
        return [
            'minLen:3',
        ];
    }
}
