<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function http_get($url, $token = null) {
        $client = new \GuzzleHttp\Client();
        if(isset($token) && strlen(trim($token) ) > 0) {
            $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Bearer '.trim($token) ] ] );
        }

        $response = $client->request('GET', $url);
        $value = json_decode($response->getBody(),true);

        return $value;
    }

    protected function http_post($url, $body = null){
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, ['form_params' => $body]);
        $value = json_decode($response->getBody(),true);

        return $value;
    }

}
