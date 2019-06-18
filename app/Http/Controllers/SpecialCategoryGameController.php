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
        $categories = Category::where($name, true)->get();
        if (!empty($categories)) {
            $games = $this->build_game_response($categories);
            $games = collect($games)->unique();
            $games = $games->values()->all();
            $data = [
                'code' => 200,
                'status' => 'success',
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

    public function build_game_response($categories)
    {
        $data_array = [];
        foreach ($categories as $category) {
            foreach ($category->games as $game) {
                array_push($data_array, $this->build_show_response($game));
            }
        }
        return $data_array;
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
        if (sizeof($marks) > 0) {
            $marks = $this->array_half($marks);
        } else {
            $marks = "N/A";
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
    public function array_half($array)
    {
        $sum = 0;
        foreach ($array as $iterator) {
            $sum += $iterator;
        }
        $mark = $sum / sizeof($array);
        return round($mark);
    }
}
