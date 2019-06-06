<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Movie;
use App\Category;

class CategoryMovieController extends Controller
{
    
    public function show($title)
    {
        $categories = Category::where('title', 'like', '%' . $title . '%')->get();
        $games=[];
        foreach ($categories as $category) {
            foreach ($category->movies as $movie) {
                array_push($movie, Movies::find($movie->id));
            }
        }
        array_unique($movies);
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
                'message' => 'Movie not found'
            ];
        }
        return response()->json($data);
    }

}