<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\response;
use App\Game;
use App\Category;

class SpecialCategoryGameController extends Controller
{

    public function show($name)
    {
        $games = [];
        $categories = Category::where($name, true)->get();
        if (!empty($categories)) {
            foreach ($categories as $category) {
                foreach ($category->games as $game) {
                    array_push($games, Game::find($game->id));
                }
            }
            $games = array_unique($games);
            $data = [
                'code' => 200,
                'status' => 'succes',
                'Games' => $games
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'game not found'
            ];
        }
        return response()->json($data);
    }
}
