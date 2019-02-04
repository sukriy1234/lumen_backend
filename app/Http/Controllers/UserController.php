<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Middleware\CorsMiddleware;

class UserController extends Controller
{
    protected $user_service;
    public function __construct(UserService $UserService)
    {
        $this->user_service =  $UserService;
    }
    public function search()
    {
		return $this->user_service->search();
    }
    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
           "username"   => "required",
           "password"   => "required"
         ]);

        if ($validator->fails()) {
            $array = array(
               'success' => false,
               'message' => $validator->errors()->first()
            );
            return $array;
        } else {
            return($this->user_service->login($request->username, $request->password));
        }
    }
    public function logout(Request $request)
    {
        return($this->user_service->logout($request->header('api_token')));
    }
}
