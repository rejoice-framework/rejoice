<?php

    namespace App\Menus;

    class Welcome extends Menu
    {
        public function message()
        {
            return [
                'Welcome to IDEAL ',
                'Golden Jubilee Promo.',
                '',
                '',
                'Enter Unique Code ',
            ];
        }

        public function defaultNextMenu()
        {
            return 'Customer::RedeemerName';
        }



        public function validate($response)
        {
            return [
                'minLen:8'
            ];
        }
    }
