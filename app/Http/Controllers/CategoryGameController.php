<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Game;
use App\Category;

class CategoryGameController extends Controller
{

    public function show($name)
    {
        $games = [];
        if (is_numeric($name)) {
            $category = Category::find($name);
            array_push($games, $this->build_game_response($category));
        } else {
            $categories = Category::where('name', $name)->get();
            if (sizeof($categories) != 0) {
                $games = $this->build_game_response($categories);
            }
        }
        if (!empty($games)) {
            $data = [
                'code' => 200,
                'status' => 'succes',
                'Games' => $games
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'there is no game for that category'
            ];
        }
        return response()->json($data);
    }

    public function build_game_response($categories)
    {
        $data_array = [];
        foreach ($categories as $category) {
            foreach ($category->games as $game) {
                array_push($data_array, Game::find($game->id));
            }
        }
        return $data_array;
    }
}
