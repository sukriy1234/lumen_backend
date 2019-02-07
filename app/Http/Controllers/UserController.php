<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends MainController
{
    protected $user_service;

    public function __construct(UserService $UserService)
    {
        $this->user_service = $UserService;
    }

    public function search()
    {
        return $this->user_service->search();
    }

    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $array = [
                'success' => false,
                'message' => $validator->errors()->first(),
            ];

            return $array;
        }

        return $this->user_service->login($request->username, $request->password);
    }

    public function logout(Request $request)
    {
        return $this->user_service->logout(parent::api($request));
    }
}
