<?php

namespace App\Services;

use App\User;

class UserService
{
    public function login($username, $password)
    {
        $array = [];
        $data = User::where('username', $username)->first();
        if ($data) {
            $hasher = app()->make('hash');
            if ($hasher->check($password, $data->password)) {
                User::where('username', $username)
                    ->where('password', $data->password)
                    ->update(['api_token' => str_random(50)]);

                $array = [
                    'success' => true,
                    'message' => json_encode(User::where([['username', $username], ['password', $data->password]])->get()->toArray()),
                ];
            } else {
                $array = [
                    'success' => false,
                    'message' => 'Wrong Password!',
                ];
            }
        } else {
            $array = [
                'success' => false,
                'message' => 'Username Not Found!',
            ];
        }

        return $array;
    }

    public function logout($api_token)
    {
        $array = [];
        $user = User::where('api_token', $api_token)->first();
        if ($user) {
            User::where('api_token', $api_token)
                ->update(['api_token' => '']);
            $array = [
                'success' => true,
                'message' => 'Sucess Logout',
            ];
        } else {
            $array = [
                'success' => false,
                'message' => 'Unknown',
            ];
        }

        return $array;
    }

    public function search()
    {
        return User::all('username as name');
    }
}
