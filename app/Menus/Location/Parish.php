<?php

namespace App\Menus\Location;

use App\Menus\Menu;

class Parish extends Menu
{
    public $resp = [];

    public function before()
    {
        $_id = $this->previousResponses('Location::Deanery');

        $this->resp =  get_auxes_by_id('aux-parishes', $_id);
    }
    public function message()
    {
        return [
            'Parish Registration',
            '',
            'Select your Parish',
            '',
        ];

    }

    public function actions()
    {
        $actions = [];
        foreach($this->resp as $key => $datum){
            $actions[ $key + 1]=[
                'display'=>$datum['name'],
                'next_menu'=>'Location::Station',
                'save_as'=>$datum['id']
            ];
        }
        return $this->withBack($actions);
    }
}
