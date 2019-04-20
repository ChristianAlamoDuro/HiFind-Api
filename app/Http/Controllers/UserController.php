<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function pruebas(Request $request) {
        return "pruebas user";
    }

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

                $pwd = password_hash($params_array['password'], PASSWORD_BCRYPT, ['cost' => 4]);
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
        // Cifrar contraseña
        // Controlar si el usuario existe
        // Crear usuario

        // Respuesta
        return response()->json($data, $data['code']);
    }

    public function login(Request $request) {
        return "login user";
    }
}
