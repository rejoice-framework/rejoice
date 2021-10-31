<?php

namespace App\Menus\BioData;

use App\Menus\Menu;

class Gender extends Menu
{
    public $resp = [];

    public function before()
    {
        $this->resp =  get_auxes('genders');
    }
    public function message()
    {
        return [
            'Parish Registration',
            '',
            'Select your Gender',
            '',
        ];

    }


    public function actions()
    {
        $actions = [];
        foreach($this->resp as $key => $datum){
            $actions[ $key + 1]=[
                'display'=>$datum['name'],
                'next_menu'=>'BioData::DateOfBirth',
                'save_as'=>$datum['id']
            ];
        }
        return $this->withBack($actions);
    }

}
