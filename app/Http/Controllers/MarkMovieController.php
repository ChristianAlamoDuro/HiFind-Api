<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Movie;
use Illuminate\Support\Facades\DB;

class MarkMovieController extends Controller
{
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        $mark = $params_array['mark'];
        $user_id = $params_array['user_id'];
        $movie_id = $params_array['movie_id'];
        $data = DB::insert("insert into marks_users_movies (movie_id,user_id,mark) values($movie_id,$user_id,$mark)");
        if ($data) {
            $dataResponse = [
                'code' => 200,
                'status' => 'succes',
            ];
        }else{
            $dataResponse = [
                'code' => 404,
                'status' => 'error',
            ];
        }
        return response()->json($dataResponse); 
    }
}
