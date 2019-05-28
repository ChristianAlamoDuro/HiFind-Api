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
        $categories = Category::where('name', 'like', '%' . $name . '%')->get();
        $games=[];
        foreach ($categories as $category) {
            foreach ($category->games as $game) {
                array_push($games, Game::find($game->id));
            }
        }
        array_unique($games);
        if (!is_null($categories)) {
            $data = [
                'code' => 200,
                'status' => 'succes',
                $games
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
