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
        $movie_id = $params_array['movie_id'];
        $user_id = $params_array['user_id'];
        $mark = $params_array['mark'];
        if (empty(DB::select("select * from marks_users_movies where movie_id=$movie_id and user_id=$user_id"))) {
            $data = DB::insert("insert into marks_users_movies (movie_id,user_id,mark) values($movie_id,$user_id,$mark)");
        } else {
            $data = DB::update("update marks_users_movies set mark=$mark where movie_id=$movie_id and user_id=$user_id");
        }

        $dataResponse = [
            'code' => 200,
            'status' => 'success',
            'data' => $data
        ];
        return response()->json($dataResponse);
    }
}
