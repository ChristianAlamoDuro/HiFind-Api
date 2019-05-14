<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{

    public function register(Request $request) {
        $json = $request->input('json',null);
        $params_array = json_decode($json, true);


        if (!empty($params_array)) {
            $params_array = array_map('trim', $params_array);
            $validate = \Validator::make($params_array,[
                'username' => 'required|alpha_num|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ]);
    
            if ($validate->fails()) {
                $data = array(
                    'status' => 'error' ,
                    'code' => 404 ,
                    'message' => 'The user could not create',
                    'errors' => $validate->errors()
                );
            }else {

                $pwd = hash('sha256',$params_array['password']);
                $user = new User();
                $user->username = $params_array['username'];
                $user->email = $params_array['email'];
                $user->password = $pwd;
                $user->role = 'ROLE_USER';
                $user->save();

                $data = array(
                    'status' => 'success' ,
                    'code' => 200 ,
                    'message' => 'User create successfull'            
                );
            }
        } else {
            $data = array(
                'status' => 'error' ,
                'code' => 404 ,
                'message' => 'The data sent is not correct',
            );
        }

        return response()->json($data, $data['code']);
    }

    public function login(Request $request) {
        $jwtAuth = new \JwtAuth();
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        $validate = \Validator::make($params_array,[
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if ($validate->fails()) {
            $data = array(
                'status' => 'error' ,
                'code' => 404 ,
                'message' => 'The user could not login',
                'errors' => $validate->errors()
            );
        } else {
            $pwd = hash('sha256',$params_array['password']);
            $signUp = $jwtAuth->signUp($params_array['email'], $pwd);
            if (!empty($params_array['getToken'])) {
                $signUp = $jwtAuth->signUp($params_array['email'], $pwd, true);
            }
        }
        return response()->json($signUp, 200);
    }
    /**
     * Actualizar los datos del usuario
     */
    public function update( Request $request) {
        $token = $request->header(('Authorization'));
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if ($checkToken) {
            echo "login ok";
        } else {
            echo "login fail";
        }
        die();
    }
}
