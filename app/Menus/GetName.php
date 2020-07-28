<?php
namespace App\Menus;

use Prinx\Str;

class GetName extends Menu
{

    public function message()
    {
        return "Kindly, input your name:";
    }

    public function actions()
    {
        return $this->backAction();
    }

    public function defaultNextMenu()
    {
        return 'say_hello';
    }

    public function validate()
    {
        return 'alphabetic|min_len:3|max_len:50';
    }

    public function saveAs($response)
    {
        return Str::capitalise($response);
    }
}
