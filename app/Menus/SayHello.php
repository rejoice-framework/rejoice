<?php
namespace App\Menus;

class SayHello extends Menu
{

    public function message()
    {
        $name = $this->userPreviousResponses('get_name');

        return "Hello {$name}!\nGlad to meet you!";
    }
}
