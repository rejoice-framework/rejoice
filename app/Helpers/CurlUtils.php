<?php


namespace App\Helpers;


use App\Menus\Menu;
use function Prinx\Dotenv\env;

class CurlUtils
{
    public static function callAPI($method,$payload,$endpoint)
    {

//        $header = Menu::aimvestApiKey();
//        $headers = array('Content-Type:application/json','Api-Authorization: Basic '. $header);
	    $headers = array('Content-Type:application/json');

	    $curl_handle = curl_init();
        switch ($method){
            case "POST":
                curl_setopt($curl_handle, CURLOPT_POST, 1);
                if ($payload)
                    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $payload);
                break;
            case "PUT":
                curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($payload)
                    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $payload);
                break;
            case "GET":
                if ($payload)
                    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $payload);
                break;
                default:
                if ($payload)
                    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $payload);
        }
        curl_setopt($curl_handle, CURLOPT_URL, $endpoint);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl_handle);
        $err = curl_error($curl_handle);
//        dd($err);
        curl_close($curl_handle);
        return $result;
    }
}
