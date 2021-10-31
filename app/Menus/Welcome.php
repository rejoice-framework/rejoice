<?php

    namespace App\Menus;

    class Welcome extends Menu
    {
        public function message()
        {
            return [
                'Welcome to the Catholic Youth Registration Portal.',
                '',
                'Select an Action to Proceed'
            ];
        }


        public function actions()
        {
            return [
                '1' => [
                    'display' => 'Register',
                    'next_menu' => 'Location::Diocese',
                    'save_as' => 'registration',
                ],
                '2' => [
                    'display' => 'View Status',
                    'next_menu' => 'Info::RegisterStatus',
                    'save_as' => 'info',
                ],
                '3' => [
                    'display' => 'Add Church Society',
                    'next_menu' => 'Addons::Society',
                    'save_as' => 'addons',
                ],
                '4' => [
                    'display' => 'Add My Sacrament',
                    'next_menu' => 'Addons::Sacrament',
                    'save_as' => 'addons',
                ],
            ];
        }



    }
