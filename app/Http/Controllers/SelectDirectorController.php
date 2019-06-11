<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Director;
use App\Movie;


class SelectDirectorController extends Controller
{
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (Director::find($params_array['id'])) {

            $data = [
                'code' => 200,
                'status' => 'success',
                'director' => Director::where('id', '=', $params_array['id'])->get()
            ];

            $dataResponse = [
                'code' => 200,
                'status' => 'success',
                'director' => $data
            ];
            
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'director not found'
            ];
        }
        return response()->json($data);
    }
}
