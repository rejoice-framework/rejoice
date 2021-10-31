<?php

namespace App\Menus\Occupation;

use App\Menus\Menu;

class Group extends Menu
{
    public $resp = [];

    public function before()
    {
        $this->resp =  get_auxes('aux-occupational-groups');

        //aux-occupational-groups
    }

    public function message()
    {
        return [
            'Parish Registration',
            '',
            'Select your Occupational Group',
            '',
        ];

    }

    public function actions()
    {
        $actions = [];
        foreach($this->resp as $key => $datum){
            $actions[ $key + 1]=[
                'display'=>$datum['name'],
                'next_menu'=>'Addons::Society',
                'save_as'=>$datum['id']
            ];
        }
        return $this->withBack($actions);
    }
}
