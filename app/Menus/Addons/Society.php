<?php

namespace App\Menus\Addons;

use App\Menus\Menu;

class Society extends Menu
{
    public $resp = [];

    public function before()
    {
        $this->resp =  get_auxes('aux-societies');

        //aux-occupational-groups
    }

    public function message()
    {
        return [
            'Youth Registration',
            '',
            'Select your Society Association (Optional)',
            '',
        ];

    }


    public function actions()
    {
        $actions = [];
        foreach($this->resp as $key => $datum){
            $actions[ $key + 1]=[
                'display'=>$datum['name'],
                'next_menu'=>'Addons::Sacrament',
                'save_as'=>$datum['id']
            ];
        }
        return $this->withBack($actions);
    }
}
