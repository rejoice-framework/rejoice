<?php

    use App\Helpers\CurlUtils;
    use Prinx\Utils\HTTP;
    use function Prinx\Dotenv\env;

    function api_caller($method, $payload, $endpoint, $headers)
    {
        $curl_handle = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl_handle, CURLOPT_POST, 1);
                if ($payload) {
                    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $payload);
                }
                break;
            case "PUT":
                curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($payload) {
                    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $payload);
                }
                break;
            case "GET":
                if ($payload) {
                    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $payload);
                }
                break;
            default:
                if ($payload) {
                    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $payload);
                }
        }
        curl_setopt($curl_handle, CURLOPT_URL, $endpoint);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl_handle);
        $err = curl_error($curl_handle);
        curl_close($curl_handle);
        return $result;
    }

    function get_genders()
    {
        $headers = array('Content-Type:application/json');

        $payload = [];

        $endpoint = env('API_BASE_URL').'genders';

        $resp = CurlUtils::callAPI('GET', $payload, $endpoint, $headers);
        $resp = json_decode($resp, true);

        $gender = [];
        foreach ($resp['data'] as $res) {
            $gender[] = $res['name'];
        }
         //log_JSON_file($gender,'GENDER');
        return $gender;
    }

    function get_auxes($service_name)
    {
        $headers = array('Content-Type:application/json');
        $payload = [];

        $endpoint = env('API_BASE_URL').$service_name;

        $resp = CurlUtils::callAPI('GET', $payload, $endpoint, $headers);
       // log_JSON_file($resp,'FO');
        $data = json_decode($resp, true);
       // log_JSON_file($data,'FO');
        return $data['data'];
    }

    function get_auxes_by_id($service_name, $id)
    {
        $headers = array('Content-Type:application/json');
        $payload = [];

        $endpoint = env('API_BASE_URL').$service_name.'/'.$id;

        $resp = CurlUtils::callAPI('GET', $payload, $endpoint, $headers);

        $data = json_decode($resp, true);

        return $data['data'];

    }
