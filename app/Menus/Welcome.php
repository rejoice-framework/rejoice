<?php
namespace App\Menus;

class Welcome extends Menu
{
    public function message()
    {
        return 'It works :D';
    }

    public function actions()
    {
        return [
            '1' => [
                'display' => 'End',
                'next_menu' => '__end',
            ],
        ];
    }
}
