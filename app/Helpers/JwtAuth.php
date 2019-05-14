<?php
namespace App\Helpers;


use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

class JwtAuth {
    // Buscar si existe el usuario email contrasaeña
    // Comprobar si son correctas
    // Generar el token con los datos del usuario
    // Devolver los datos

    public static $key;

    public function __construct() {
        self::$key = 'key_user-130913';
    }
    
    public function signUp($email, $password, $getToken=null) {
        $signUp = false;
        $user = User::where([
            'email' => $email,
            'password' => $password
        ])->first();
        
        if(is_object($user)) {
            $signUp = true;
        }
        if ($signUp) {
            /**
             * sub --> Referencia al id del usuario
             * email
             * username
             * iat --> Fecha creacion del token
             */
            $token = array(
                'sub'       =>      $user->id,
                'email'     =>      $user->email,
                'username'  =>      $user->username,
                'iat'       =>      time(),
                'exp'       =>      time() + (43200)
            );
            /**
             * token --> JSON 
             * key
             * Algoritmo de cifrado
             */
            $jwt = JWT::encode($token, self::$key, 'HS256');
            $decode = JWT::decode($jwt, self::$key, ['HS256']);
            if (is_null($getToken)) {
                $data =  $jwt;
            } else {
                $data = $decode;
            }
        } else {
            $data = array(
                'status' => 'error',
                'message' => 'Login incorrect'
            );
        } 

        return $data;
    }

    public function checkToken($jwt, $getIdentity = false) {
        $auth = false;

        try {
            $jwt = str_replace('"', '', $jwt);
            $decoded = JWT::decode($jwt, self::$key, ['HS256']);
        } catch(\UnexpectedValueException $exception) {
            $auth = false;
        } catch(\DomainException $exception) {
            $auth = false;
        }

        if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)){
            $auth = true;
        } else {
            $auth = false;
        }

        if ($getIdentity) {
            return $decoded;
        }

        return $auth;
    }
}

?>