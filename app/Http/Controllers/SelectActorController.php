<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Actor;
use Illuminate\Support\Facades\DB;

class SelectActorController extends Controller
{
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (Actor::find($params_array['id'])) {

            $data = [
                'code' => 200,
                'status' => 'success',
                'actor' => Actor::where('id', '=', $params_array['id'])->get()
            ];

            $dataResponse = [
                'code' => 200,
                'status' => 'success',
                'actor' => $data
            ];
            
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'actor not found'
            ];
        }
        return response()->json($data);
    }
}
