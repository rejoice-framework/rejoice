<?php

namespace App\Menus\Location;

use App\Menus\Menu;

class Deanery extends Menu
{
    public $resp = [];

    public function before()
    {
        $_id = $this->previousResponses('Location::Diocese');

        $this->resp =  get_auxes_by_id('aux-deaneries', $_id);
    }
    public function message()
    {
        return [
            'Parish Registration',
            '',
            'Select your Deanery',
            '',
        ];

    }

    public function actions()
    {
        $actions = [];
        foreach($this->resp as $key => $datum){
            $actions[ $key + 1]=[
                'display'=>$datum['name'],
                'next_menu'=>'Location::Parish',
                'save_as'=>$datum['id']
            ];
        }
        return $this->withBack($actions);
    }
}
