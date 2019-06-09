<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Director;
use Illuminate\Http\response;
use App\Movie;
use Illuminate\Support\Facades\DB;

class DeleteDirectorController extends Controller
{
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        if (Director::find($params_array['id'])) {
            Director::find($params_array['id'])->delete();
            $data = [
                'code' => 200,
                'status' => 'succes',
                'message' => 'director deleted'
            ];
        } else {
            $data = [
                'code' => 400,
                'status' => 'succes',
                'message' => 'director not found'
            ];
        }
        return response()->json($data);
    }
}
