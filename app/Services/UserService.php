<?php
namespace App\Services;

use App\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function login($username, $password)
    {
        $data = User::where('username', $username)->first();
        if ($data) {
            $data = User::where('password', $password)->first();
            if ($data) {
                User::where('username', $username)
                         ->where('password', $password)
                         ->update(['api_token' => str_random(50)]);

                $array = array(
                  'success' => true,
                  'message' => json_encode(User::where([['username',$username], ['password',$password]])->get()->toArray())
               );
            } else {
                $array = array(
                   'success' => false,
                   'message' => 'password Salah!'
                );
            }
        } else {
            $array = array(
               'success' => false,
               'message' => 'Username tidak terdaftar!'
            );
        }
        return $array;
    }
    public function logout($api_token)
    {
        $user = User::where('api_token', $api_token)->where('api_token', '<>', '')->first();
        if ($user) {
            User::where('api_token', $api_token)
                 ->update(['api_token' => '']);
            $array = array(
             'success' => true,
             'message' => 'Sucess Logout'
           );
        } else {
            $array = array(
             'success' => false,
             'message' => 'Unknown'
           );
        }
        return $array;
    }
    public function search()
    {
        return $data = User::all('username as name');
    }
}
