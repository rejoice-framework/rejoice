<?php

namespace App\Menus\Location;

use App\Menus\Menu;

class Diocese extends Menu
{
    public $resp = [];

    public function before()
    {
        $this->resp =  get_auxes('aux-dioceses');
    }
    public function message()
    {
        return [
            'Parish Registration',
            '',
            'Select your Diocese',
            '',
        ];

    }

    public function actions()
    {
        $actions = [];
        foreach($this->resp as $key => $datum){
            $actions[ $key + 1]=[
                'display'=>$datum['name'],
                'next_menu'=>'Location::Deanery',
                'save_as'=>$datum['id']
            ];
        }
        return $this->withBack($actions);
    }
}
