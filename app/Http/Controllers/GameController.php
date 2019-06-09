<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\response;
use App\Game;
use Illuminate\Support\Facades\DB;
use App\Category;
use App\Validation;

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
            'games' => $data
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
        $data = [];
        if (!is_null($games)) {
            foreach ($games as $game) {
                array_push($data, $this->build_show_response($game));
            }
            $dataResponse = [
                'code' => 200,
                'status' => 'succes',
                'games' => $data
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
        if (is_object(json_decode($json))) {
            $user_id = json_decode($json)->user_id;
            if (Validation::adminValidate($user_id)) {
                $image = file_get_contents($request->file('image'));
                $image = base64_encode($image);
                $params_array = json_decode($json, true);
                $validate = \Validator::make($params_array, [
                    'name' => 'required',
                    'out_date' => 'required',
                    'public_directed' => 'required',
                    'duration' => 'required',
                    'sinopsis' => 'required',
                    'categories' => 'required',
                ]);

                if ($validate->fails()) {
                    $data = [
                        'code' => 400,
                        'status' => 'succes',
                        'message' => 'Validation error'
                    ];
                } else {
                    if (isset($params_array['id'])) {
                        $data = $this->prepare_update($params_array, $image);
                    } else {
                        $data = $this->prepare_store($params_array, $image);
                    }
                }
            } else {
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Error this user role dont have permission'
                ];
            }
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Wrong data values'
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
            'out_date' => $game->out_date,
            'public_directed' => $game->public_directed,
            'duration' => $game->duration,
            'image' => $game->image,
            'categories' => $categories,
            'marks' => $marks,
            'id' => $game->id
        ];
    }

    public function prepare_update($params_array, $image)
    {
        $params_to_update = [
            'name' => $params_array['name'],
            'sinopsis' => $params_array['sinopsis'],
            'out_date' => $params_array['out_date'],
            'public_directed' => $params_array['public_directed'],
            'duration' => $params_array['duration'],
            'image' => $image
        ];
        $id = $params_array['id'];
        unset($params_array['id']);
        unset($params_array['created_at']);
        $game = Game::where('id', $id)->update($params_to_update);
        $game = Game::find($id);
        $categories = [];
        foreach ($params_array['categories'] as $category) {
            if (Category::find($category)) {
                array_push($categories, $category);
            }
        }
        $game->categories()->sync($categories);
        return [
            'code' => 200,
            'status' => 'succes',
            'message' => 'Game update successfull',
            'game' => $params_array
        ];
    }
    public function prepare_store($params_array, $image)
    {
        $game = new Game();
        $game->name = $params_array['name'];
        $game->duration = $params_array['duration'];
        $game->sinopsis = $params_array['sinopsis'];
        $game->out_date = $params_array['out_date'];
        $game->public_directed = $params_array['public_directed'];
        $game->image = $image;
        $game->save();
        $categories = [];
        foreach ($params_array['categories'] as $category) {
            if (Category::find($category)) {
                array_push($categories, $category);
            }
        }
        $game->categories()->attach($categories);
        return  [
            'code' => 200,
            'status' => 'succes',
            'message' => 'Game store succesfull',
            'game' => $game
        ];
    }
}
