<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\response;
use App\Game;
use Illuminate\Support\Facades\DB;
use App\Category;
use App\Validation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class GameController extends Controller
{
    public function index()
    {
        $data = [];
        $games = Game::all();
        foreach ($games as $game) {
            array_push($data, $this->build_show_response($game));
        }
        $collection = collect($data);
        $data = $collection->sortBy('name');
        $data = $data->values()->all();
        $dataResponse = [
            'code' => 200,
            'status' => 'success',
            'games' => $data
        ];
        return response()->json($dataResponse);
    }


    public function show($name)
    {
        $data = [];
        if (is_numeric($name)) {
            $games = Game::find($name);
            array_push($data, $this->build_show_response($games));
        } else {
            $games = Game::where('name', 'like', '%' . $name . '%')->get();
            foreach ($games as $game) {
                array_push($data, $this->build_show_response($game));
            }
        }
        if (!is_null($games)) {
            $collection = collect($data);
            $data = $collection->sortBy('name');
            $data = $data = $data->values()->all();
            $dataResponse = [
                'code' => 200,
                'status' => 'success',
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
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                Storage::disk('uploads')->put($image->getFilename() . '.' . $extension,  File::get($image));
                $image_name = "/public/storage/img/" . $image->getFilename() . '.' . $extension;
                $params_array = json_decode($json, true);
                $validate = \Validator::make($params_array, [
                    'name' => 'required',
                    'out_date' => 'required|date_format:d/m/Y',
                    'public_directed' => 'required',
                    'duration' => 'required',
                    'sinopsis' => 'required',
                    'categories' => 'required',
                ]);

                if ($validate->fails()) {
                    $data = [
                        'code' => 404,
                        'status' => 'error',
                        'message' => 'Validation error'
                    ];
                } else {
                    if (isset($params_array['id'])) {
                        if (Game::find($params_array['id']) != NULL) {
                            $data = $this->prepare_update($params_array, $image_name);
                        } else {
                            $data = [
                                'code' => 404,
                                'status' => 'error',
                                'message' => 'Game not found to update'
                            ];
                        }
                    } else {
                        $data = $this->prepare_store($params_array, $image_name);
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
            'marks' => $marks
        ];
    }

    public function prepare_update($params_array, $image)
    {
        $params_to_update = [
            'name' => ucfirst($params_array['name']),
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
            'status' => 'success',
            'message' => 'Game update successfull',
            'game' => [$params_to_update, 'Categories' => $categories]
        ];
    }
    public function prepare_store($params_array, $image)
    {
        $game = new Game();
        $game->name = ucfirst($params_array['name']);
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
            'status' => 'success',
            'message' => 'Game store successfull',
            'game' => $game
        ];
    }
}
