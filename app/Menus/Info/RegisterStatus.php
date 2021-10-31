<?php

namespace App\Menus\Info;

use App\Menus\Menu;

class RegisterStatus extends Menu
{
    /**
     * The message to display at the top of the screen.
     *
     * @param Rejoice\Foundation\UserResponse $previousResponses
     * @return string|array
     */
    public function message($previousResponses)
    {
        return '';
    }

    /**
     * The actions to display at the bottom of the top message.
     *
     * @param Rejoice\Foundation\UserResponse $previousResponses
     * @return array
     */
    public function actions($previousResponses)
    {
        $actions = [];

        return $this->withBack($actions);
    }
}
