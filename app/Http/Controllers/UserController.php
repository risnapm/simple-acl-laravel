<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request){
        $user = $this->http_get(env('API_URL').'/users');

        return $user;

    }
}
