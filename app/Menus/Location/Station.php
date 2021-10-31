<?php

namespace App\Menus\Location;

use App\Menus\Menu;
use App\Menus\BioData;
class Station extends Menu
{
    public $resp = [];
    public $Surname  = BioData\Surname::class;

    public function before()
    {
        $_id = $this->previousResponses('Location::Parish');

        $this->resp =  get_auxes_by_id('aux-stations', $_id);
    }
    public function message()
    {
        return [
            'Parish Registration',
            '',
            'Select your Church  Station',
            '',
        ];

    }

    public function actions()
    {
        $actions = [];
        foreach($this->resp as $key => $datum){

            $actions[ $key + 1]=[
                'display'=>$datum['name'],
                'next_menu'=>$this->Surname,
                'save_as'=>$datum['id']
            ];
        }
        return $this->withBack($actions);
    }
}
