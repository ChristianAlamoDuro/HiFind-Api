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
        $mark = $params_array['mark'];
        $user_id = $params_array['user_id'];
        $game_id = $params_array['game_id'];
        $data = DB::insert("insert into marks_users_games (game_id,user_id,mark) values($game_id,$user_id,$mark)");
        if ($data) {
            $dataResponse = [
                'code' => 200,
                'status' => 'succes',
            ];
        }else{
            $dataResponse = [
                'code' => 404,
                'status' => 'data error',
            ];
        }
        return response()->json($dataResponse); 
    }
}
