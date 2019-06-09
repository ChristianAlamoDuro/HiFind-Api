<?php
namespace App;

use Illuminate\Support\Facades\DB;

class Validation
{
    ##Para usar este metodo de validación de usuarios admin tendrás que añadir la ruta App\Validation al controller 
    ##Ahí llamas a la funcion Validation::adminValidate({aquí pasaríamos el id del usuario actualmente logueado})
    public static function adminValidate($id)
    {
        if (DB::select("select * from users where id  = $id")) {
            $user = DB::select("select role from users where id = $id");
            return $user[0]->role == "ROLE_ADMIN";
        }
        return false;
    }
}
