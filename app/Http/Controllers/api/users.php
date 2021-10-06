<?php

namespace App\Http\Controllers\api;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\Models\User;


class Users extends Controller{

    public $request;
    protected $bearer_token_secret = "bearer_shop";
    protected $refresh_token_secret = "refresh_shop";
    protected $time_token_set = 600000;
    protected $response = [ 'data' => [], 'error' => [] ];

    public function __construct(Request $request){
        $this->request = $request;
    }




    public function get(){

        $this->response['data'] = ['user' => 'get user data'];

        return $this->response;
    }

    
    public function registr(){

        $login = $this->request->input('login');
        $password = $this->request->input('password');
        $email = $this->request->input('email');


        $userDB = User::where('login', $login)->first();
        if(isset($userDB)){
            $response['messages'][] = ['type'=>'error', 'message'=>'Логин уже зарегистрирован', 'code' => 0];
        }

        if( count($response['messages']) > 0 ){ return response()->json($response); }


        $bearer_token = bcrypt($login.$email.$this->bearer_token_secret);
        $refresh_token = bcrypt($login.$email.$this->refresh_token_secret);
        $time_token = time() + $this->time_token_set;

        // $idRight = LinkRight::where('id_user', $userDB->id )->first();
        $right = Right::where('id', 1)->first();

        $userId = User::create([
            'api_key' => md5(time().$login),
            'login' => $login,
            'email' => $email,
            'password' => bcrypt($password),
            'status' => 1,

            'cent' => '{"yandex":null,"qiwi":null,"card":null,"webMoney":null}',

            'bearer_token' => $bearer_token,
            'refresh_token' => $refresh_token,
            'time_token' => $time_token
        ])->id;
        LinkRight::create(['id_user' => $userId, 'id_rights' => 1])->id;

        $response['data'] = ['bearer_token' => $bearer_token, 'refresh_token' => $refresh_token, 'time_token' => $time_token, 'right' => $right->name, 'name' => $right->ru_name]; // 
        

        return $this->response;
    }



    public function login(){

        $login = $this->request->input('login');
        $password = $this->request->input('password');

        $userDB = User::where('login', $login)->first();
        if(!isset($userDB)){ 
            $this->response['messages'][] = ['type'=>'error', 'message'=>'Пользователь не найден', 'code' => 1];
            return $this->response;
        }
        if( !Hash::check($password, $userDB->password) ){ 
            $this->response['messages'][] = ['type'=>'error', 'message'=>'Пароль не верен', 'code' => 2];
            return $this->response;
        }


        $bearer_token = bcrypt($login.$userDB->email.$this->bearer_token_secret);
        $refresh_token = bcrypt($login.$userDB->email.$this->refresh_token_secret);
        $time_token = time() + $this->time_token_set;

        User::where('id', $userDB->id)->update([
        'bearer_token' => $bearer_token,
        'refresh_token' => $refresh_token,
        'time_token' => $time_token
        ]);

        $this->response['data'] = ['access_token' => $bearer_token, 'refresh_token' => $refresh_token];

        return $this->response;
    }



    public function token(){

        $token = $this->request->input('refresh_token');

        $userDB = User::where('refresh_token', $token)->first();
        if(!isset($userDB)){
            $this->response['messages'][] = ['type'=>'error', 'message'=>'Пользователь не найден', 'code' => 1];
            return $this->response;
        }

        $bearer_token = bcrypt($userDB->login.$userDB->email.$this->bearer_token_secret);
        $time_token = time() + $this->time_token_set;

        User::where('id', $userDB->id)->update([
            'bearer_token' => $bearer_token,
            'time_token' => $time_token
        ]);
        
        $this->response['data'] = ['bearer_token' => $bearer_token, 'refresh_token' => $token];


        return $this->response;
    }



    public function exits(){

        $userId = $this->request->userId;

        User::where('id', $userId)->update([
            'bearer_token' => '',
            'refresh_token' => '',
            'time_token' => 0
        ]);

        return $this->response;
    }
}