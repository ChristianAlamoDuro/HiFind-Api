<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Movie;
use App\Category;
use App\Actor;
use App\Director;

class SpecialCategoryMovieController extends Controller
{

    public function show($name)
    {
        $categories = Category::where($name, true)->get();
        
        if (!empty($categories)) {
            $movies=$this->build_movie_response($categories);
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
        return response()->json($data);
    }

    public function build_movie_response($categories)
    {
        $data_array = [];
        foreach ($categories as $category) {
            foreach ($category->movies as $movie) {
                array_push($data_array, $this->build_show_response($movie));
            }
        }
        return $data_array;
    }

    public function build_show_response($movie)
    {
        $categories = [];
        $marks = [];
        $actors = [];
        $directors = [];


        foreach ($movie->categories_movies as $category) {
            array_push($categories, $category->name);
        }
        foreach ($movie->marks_movies as $mark) {
            array_push($marks, $mark->pivot->mark);
        }
        foreach ($movie->directors_movies as $director) {
            array_push($directors, $director->name);
        }
        foreach ($movie->actors_movies as $actor) {
            array_push($actors, $actor->name);
        }


        return [
            'id' => $movie->id,
            'title' => $movie->title,
            'sinopsis' => $movie->sinopsis,
            'out_date' => $movie->out_date,
            'film_producer' => $movie->film_producer,
            'public_directed' => $movie->public_directed,
            'duration' => $movie->duration,
            'image' => $movie->image,
            'categories' => $categories,
            'actors' => $actors,
            'directors' => $directors,
            'marks' => $marks            
        ];
    }
}
