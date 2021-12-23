<?php

namespace App\Menus\Customer;

use App\Menus\Menu;

class RedeemerName extends Menu
{
    public $resp = [];

    public function before()
    {

       //$redeem_code = $this->previousResponses('Welcome');

        //$responses = $this->previousResponses();

       // $this->respond(json_encode($this->response()));

       /* $this->respond(
            [
                'Thank you for buying',
                'IDEAL 390g',
                '',
                '',
                'Enter First & Last Names'
            ]
        );*/

     /*   $payload = array(
            "redeem_code" => $redeem_code,
        );

        $payload = json_encode($payload);

        $headers = array('Content-Type:application/json');

        $req = post_data('redeem', $payload, $headers);
        if ($req['status'] == 200) {
            $this->respond(
                [
                    'Thank you for buying',
                    'IDEAL 390g',
                    '',
                    '',
                    'Enter First & Last Names'
                ]
            );
        }else{
            $this->respond([
               'Oops! Sorry.',
                'Unable to complete the redemption.'
            ]);
        }*/
    }

    /*public function message($previousResponses)
    {

        return [
            'Thank you for buying',
            'IDEAL 390g',
            '',
            '',
            'Enter First & Last Names'
        ];
    }*/

    public function message()
    {
        return [
            'Thank you for buying',
            'IDEAL 390g',
            '',
            '',
            'Enter First & Last Names',
        ];
    }

    public function defaultNextMenu()
    {
        return 'Customer::PostName';
    }

    public function validate($response)
    {
        return [
            'minLen:5',
        ];
    }
}
