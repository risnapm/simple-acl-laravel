<?php

namespace App\Http\Controllers\Api;

use App\Models\LogError;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogController extends Controller
{
    public function saveLogError(Request $request){

        $log = new LogError();

        $log->method = $request->input('method');
        $log->action = $request->input('action');
        $log->request = $request->input('request');
        $log->response = $request->input('response');
        $log->created_at = date('Y-m-d H:i:s');

        if($log->save()){
            return $this->setStatusCode(200)->respondWithArray([
                'message' => 'Success'
            ]);
        }else{
            return $this->setStatusCode(500)->respondWithArray([
                'message' => 'Fail'
            ]);
        }

    }
}
