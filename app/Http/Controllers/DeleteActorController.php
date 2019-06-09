<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actor;
use Illuminate\Http\response;
use App\Movie;
use Illuminate\Support\Facades\DB;

class DeleteActorController extends Controller
{
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        if (Actor::find($params_array['id'])) {
            Actor::find($params_array['id'])->delete();
            $data = [
                'code' => 200,
                'status' => 'succes',
                'message' => 'actor deleted'
            ];
        } else {
            $data = [
                'code' => 400,
                'status' => 'succes',
                'message' => 'actor not found'
            ];
        }
        return response()->json($data);
    }
}
