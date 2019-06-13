<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Movie;
use App\Category;

class SpecialCategoryMovieController extends Controller
{
    public function show($name)
    {
        if ($name == "is_movie" || $name == "is_game" || $name == "is_special_movie" || $name == "is_special_game"){
            $movies = [];
            $categories = Category::where($name, true)->get();
            if (!empty($categories)) {
                foreach ($categories as $category) {
                    foreach ($category->movies as $movie) {
                       
                        array_push($movies, Movie::find($movie->id));
                    }
                }
                $movies = array_unique($movies);
                
                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'movies' => $movies
                ];
            } else {
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'movie not found'
                ];
            }
        }
        else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'wrong special category'
            ];
        }
        
        return response()->json($data);
    }
}
