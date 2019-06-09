<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\response;
use App\Movie;
use Illuminate\Support\Facades\DB;

class DeleteMovieController extends Controller
{
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        if (Movie::find($params_array['id'])) {
            Movie::find($params_array['id'])->delete();
            $data = [
                'code' => 200,
                'status' => 'succes',
                'message' => 'movie deleted'
            ];
        } else {
            $data = [
                'code' => 400,
                'status' => 'failure',
                'message' => 'movie not found'
            ];
        }
        return response()->json($data);
    }
}
