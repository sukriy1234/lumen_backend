<?php

namespace App\Http\Controllers;

class MainController extends Controller
{
    public function api($request)
    {
        return $request->header('api_token');
    }
}
