<?php

namespace App\Menus\BioData;

use App\Menus\Address\Email;
use App\Menus\Menu;

class DateOfBirth extends Menu
{
    public $newClass = Email::class;

    public function message()
    {
        return [
            'Parish Registration',
            '',
            'Enter your Date Of Birth e.g 1992/01/03',
        ];
    }

    public function defaultNextMenu()
    {
        return $this->newClass;
    }

    public function validate($response)
    {
        return [
            'minLen:5',
        ];
    }
}
