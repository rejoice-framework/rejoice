<?php

namespace App\Menus\BioData;

use App\Menus\Menu;

class OtherName extends Menu
{
    public function before()
    {
        $responses = $this->previousResponses();
        // $name = $responses->has('enter_surname') ? $responses->get('enter_surname') : 'dear user';

        //$this->respond(json_encode($this->response()));
    }

    public function message()
    {
        return [
            'Parish Registration',
            '',
            'Enter your Other name(s) ',
        ];
    }

    public function defaultNextMenu()
    {
        return 'BioData::Gender';
    }
}
