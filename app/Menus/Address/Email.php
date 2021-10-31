<?php

namespace App\Menus\Address;

use App\Menus\Menu;

class Email extends Menu
{

    public function message()
    {
        return [
            'Parish Registration',
            '',
            'Enter Email Address (Optional)',
            '',
        ];

    }

    public function defaultNextMenu()
    {
        return 'Address::DigitalAddress';
    }

    public function actions()
    {
        return $this->withBack([
            "1"=>[
                'display'=>'Next',
                'next_menu'=>'Address::DigitalAddress'
            ]
        ]);
    }




}
