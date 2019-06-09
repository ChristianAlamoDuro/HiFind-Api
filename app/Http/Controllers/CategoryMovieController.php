<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Movie;
use App\Category;

class CategoryMovieController extends Controller
{
    
    public function show($name)
    {
        $categories = Category::where('name', 'like', '%' . $name . '%')->get();
        $movies=[];
        if (count($categories)>0){
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
                'message' => 'category not found'
            ];
        }
        return response()->json($data);
    }

}