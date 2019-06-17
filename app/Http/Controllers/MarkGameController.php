<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;
use Illuminate\Support\Facades\DB;

class MarkGameController extends Controller
{
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        $game_id = $params_array['game_id'];
        $user_id = $params_array['user_id'];
        $mark = $params_array['mark'];
        if (empty(DB::select("select * from marks_users_games where game_id=$game_id and user_id=$user_id"))) {
            $data = DB::insert("insert into marks_users_games (game_id,user_id,mark) values($game_id,$user_id,$mark)");
        } else {
            $data = DB::update("update marks_users_games set mark=$mark where game_id=$game_id and user_id=$user_id");
        }

        $dataResponse = [
            'code' => 200,
            'status' => 'success',
        ];

        return response()->json($dataResponse);
    }
}
