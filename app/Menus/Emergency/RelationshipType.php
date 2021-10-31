<?php

namespace App\Menus\Emergency;

use App\Menus\Menu;

class RelationshipType extends Menu
{
    public $resp = [];

    public function before()
    {
        $this->resp = get_auxes('aux-emergency-contact-types');
    }

    public function message()
    {
        return [
            'Parish Registration',
            '',
            'Select your Emergency Contact Relationship',
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
                'next_menu'=> 'Education::HighestLevel',
                'save_as'  => $datum['id'],
            ];
        }

        return $this->withBack($actions);
    }

    /* public function defaultNextMenu()
     {
         return 'Education::HighestLevel';
     }*/
}
