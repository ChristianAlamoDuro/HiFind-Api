<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Movie;
use Illuminate\Support\Facades\DB;

class SelectMovieController extends Controller
{
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (Movie::find($params_array['id'])) {

            $data = [
                'code' => 200,
                'status' => 'success',
                'movie' => Movie::where('id', '=', $params_array['id'])->get()
            ];

            $dataResponse = [
                'code' => 200,
                'status' => 'success',
                'movie' => $data
            ];
            
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Movie not found'
            ];
        }
        return response()->json($data);
    }
}
