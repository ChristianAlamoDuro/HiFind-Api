<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\response;
use App\Game;
use Illuminate\Support\Facades\DB;

class DeleteGameController extends Controller
{
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        if (Game::find($params_array['id'])) {
            $game_to_delete = Game::find($params_array['id'])->delete();
            $data = [
                'code' => 200,
                'status' => 'succes',
                'message' => 'Game deleted'
            ];
        } else {
            $data = [
                'code' => 400,
                'status' => 'succes',
                'message' => 'Game not found'
            ];
        }
        return response()->json($data);
    }
}
