<?php

namespace App\Menus\Address;

use App\Menus\Menu;

class ResidentialStatus extends Menu
{
    public $resp = [];

    public function before()
    {
        $this->resp = get_auxes('aux-residential-statuses');
    }

    public function message()
    {
        return [
            'Parish Registration',
            '',
            'Select your Residential Status',
            '',
        ];
    }

    /* public function defaultNextMenu()
     {
         return 'Education::DigitalAddress';
     }*/

    public function actions()
    {
        $actions = [];
        foreach ($this->resp as $key => $datum) {
            $actions[$key + 1] = [
                'display'  => $datum['name'],
                'next_menu'=> 'Emergency::ContactName',
                'save_as'  => $datum['id'],
            ];
        }

        return $this->withBack($actions);
    }
}
