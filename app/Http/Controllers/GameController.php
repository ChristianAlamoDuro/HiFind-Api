<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\response;
use App\Game;

class GameController extends Controller
{
    public function index()
    {
        $data = [];
        $games = Game::all();
        foreach ($games as $game) {
            array_push($data, $this->build_show_response($game));
        }
        $dataResponse = [
            'code' => 200,
            'status' => 'succes',
            'games'=>$data
        ];
        $data = [
            'code' => 200,
            'status' => 'succes',
        ];
       
        return response()->json($dataResponse);
    }


    public function show($name)
    {
        $games = Game::where('name', 'like', '%' . $name . '%')->get();
        $data=[];
        if (!is_null($games)) {
            foreach ($games as $game) {
                array_push($data, $this->build_show_response($game));
            }
            $dataResponse = [
                'code' => 200,
                'status' => 'succes',
                'games'=>$data
            ];
        } else {
            $dataResponse = [
                'code' => 404,
                'status' => 'error',
                'message' => 'game not found'
            ];
        }
        return response()->json($dataResponse);
    }

    public function store(Request $request)
    {
        $json = $request->input('json', null);

        if ($json) {
            $params_array = json_decode($json, true);

            $validate = \Validator::make($params_array, [
                'name' => 'required',
                'out_date' => 'required',
                'public_directed' => 'required',
                'duration' => 'required',
                'sinopsis' => 'required',
                'image' => 'required',
                'categories' => 'required'
            ]);

            if ($validate->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'succes',
                    'message' => 'Error de validación no se ha guardado la categoría'
                ];
            } else {
                $game = new Game();
                $game->name = $params_array['name'];
                $game->duration = $params_array['duration'];
                $game->sinopsis = $params_array['sinopsis'];
                $game->out_date = $params_array['out_date'];
                $game->public_directed = $params_array['public_directed'];
                $game->image = $params_array['image'];
                $game->save();
                $categories = [];
                foreach ($params_array['categories'] as $category) {
                    array_push($categories, $category);
                }
                $game->categories()->attach($categories);
                $data = [
                    'code' => 200,
                    'status' => 'succes',
                    'message' => 'Juego guardada satisfactoriamente',
                    'game' => $game
                ];
            }
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Ups un error inesperado disculpe las molestias'
            ];
        }
        return response()->json($data);
    }

    public function update($id, Request $request)
    {
        $json = $request->input('json', null);

        if ($json) {
            $params_array = json_decode($json, true);
            $validate = \Validator::make($params_array, [
                'name' => 'required',
                'sinopsis' => 'required',
                'out_date' => 'required',
                'public_directed' => 'required',
                'duration' => 'required',
                'image' => 'required'
            ]);

            if ($validate->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'succes',
                    'message' => 'Error de validación no se ha actualizado el juego'
                ];
            } else {
                unset($params_array['id']);
                unset($params_array['created_at']);

                $game = Game::where('id', $id)->update($params_array);
                $data = [
                    'code' => 200,
                    'status' => 'succes',
                    'message' => 'Juego actualizado satisfactoriamente',
                    'game' => $params_array
                ];
            }
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Ups un error inesperado disculpe las molestias'
            ];
        }
        return response()->json($data);
    }

    public function build_show_response($game)
    {
        $categories = [];
        $marks = [];
        foreach ($game->categories as $category) {
            array_push($categories, $category->name);
        }
        foreach ($game->marks_games as $mark) {
            array_push($marks, $mark->pivot->mark);
        }
        return [
            'name' => $game->name,
            'sinopsis' => $game->sinopsis,
            'out_date' => $game->out_date, 'public_directed' => $game->public_directed, 'duration' => $game->duration,
            'image' => $game->image,
            'categories' => $categories,
            'marks' => $marks
        ];
    }
}
