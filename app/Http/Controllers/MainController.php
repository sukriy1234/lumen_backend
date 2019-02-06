<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function api($request)
    {
        return $request->header('api_token');
    }
}
