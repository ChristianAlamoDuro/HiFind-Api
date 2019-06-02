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
        $categories = Category::where('name', $name)->get();
        $games = [];

        if (sizeof($categories)!=0) {
            foreach ($categories as $category) {
                foreach ($category->games as $game) {
                    array_push($games, Game::find($game->id));
                }
            }
            if (!empty($games)) {
                array_unique($games);
    
                $data = [
                    'code' => 200,
                    'status' => 'succes',
                    'Games' => $games
                ];
            }else{
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'there is no game for that category'
                ];

            }
         }
         else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'category not found'
            ];
        }
        
        return response()->json($data);
    }
}
