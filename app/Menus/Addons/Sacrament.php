<?php

namespace App\Menus\Addons;

use App\Menus\Menu;

class Sacrament extends Menu
{
    public $resp = [];

    public function before()
    {
        $this->resp =  get_auxes('aux-sacraments');

        //aux-occupational-groups
    }

    public function message()
    {
        return [
            'Youth Registration',
            '',
            'Select your Sacraments (Optional)',
            '',
        ];

    }


    public function actions()
    {
        $actions = [];
        foreach($this->resp as $key => $datum){
            $actions[ $key + 1]=[
                'display'=>$datum['name'],
                'next_menu'=>'Exits::EndRegister',
                'save_as'=>$datum['id']
            ];
        }
        return $this->withBack($actions);
    }
}
