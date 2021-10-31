<?php

namespace App\Menus\Exits;

    use App\Menus\Menu;

    class EndRegister extends Menu
    {
        public function before()
        {
            $responses = $this->previousResponses();
            // $name = $responses->has('enter_surname') ? $responses->get('enter_surname') : 'dear user';

            $this->respond(json_encode($this->response()));
        }

        public function message()
        {
            return [
                'Parish Registration',
                '',
                'Registration completed ',
            ];
        }

        public function actions($previousResponses)
        {
            return $this->withBack([
                '1' => [
                    'display'   => 'Exit',
                    'next_menu' => '__end',
                ],
            ]);
        }
    }
