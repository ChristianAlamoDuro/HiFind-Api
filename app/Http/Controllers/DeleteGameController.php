<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\response;
use App\Game;
use Illuminate\Support\Facades\DB;
use App\Validation;

class DeleteGameController extends Controller
{
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $user_id = json_decode($json)->user_id;
        if (Validation::adminValidate($user_id)) {
            $params_array = json_decode($json, true);
            if (Game::find($params_array['id'])) {
                $game_to_delete = Game::find($params_array['id'])->delete();
                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Game deleted'
                ];
            } else {
                $data = [
                    'code' => 400,
                    'status' => 'Error',
                    'message' => 'Game not found'
                ];
            }
        } else {
            $data = [
                'code' => 400,
                'status' => 'Error',
                'message' => 'This user dont have permissions'
            ];
        }

        return response()->json($data);
    }
}
