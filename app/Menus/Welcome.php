<?php
namespace App\Menus;

class Welcome extends Menu
{

    public function message()
    {
        return "Welcome to Rejoice :)";
    }

    public function actions()
    {
        return [
            '1' => [
                'display' => 'Say hello!',
                'next_menu' => 'get_name',
            ],
        ];
    }
}
