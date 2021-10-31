<?php

namespace App\Menus\Education;

use App\Menus\Menu;

class HighestLevel extends Menu
{
    public $resp = [];

    public function before()
    {
        $this->resp = get_auxes('aux-educational-levels');

        //aux-occupational-groups
    }

    public function message()
    {
        return [
            'Parish Registration',
            '',
            'Select your Highest Education Level',
            '',
        ];
    }

    public function actions()
    {
        $actions = [];
        foreach ($this->resp as $key => $datum) {
            $actions[$key + 1] = [
                'display'  => $datum['name'],
                'next_menu'=> 'Occupation::Group',
                'save_as'  => $datum['id'],
            ];
        }

        return $this->withBack($actions);
    }
}
