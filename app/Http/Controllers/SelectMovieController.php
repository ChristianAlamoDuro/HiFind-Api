<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Movie;
use App\Category;
use App\Director;
use App\Actor;

class SelectMovieController extends Controller
{
    public function show($id)
    {

        $movieSelected = Movie::find($id);
        $data = [];
        if (!is_null($movieSelected)) {

            $movies = Movie::all();

            foreach ($movies as $movie) {
                array_push($data, $this->build_show_response($movie));
                break;
            }

            $dataResponse = [
                'code' => 200,
                'status' => 'success',
                'movies' => $data
            ];
        } else {
            $dataResponse = [
                'code' => 404,
                'status' => 'error',
                'message' => 'movie not found'
            ];
        }
        return response()->json($dataResponse);
    }

    public function build_show_response($movie)
    {
        $categories = [];
        $directors = [];
        $actors = [];
        $marks = [];

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
            'out_date' => $movie->out_date,
            'public_directed' => $movie->public_directed,
            'film_producer' => $movie->film_producer,
            'duration' => $movie->duration,
            'sinopsis' => $movie->sinopsis,
            'image' => $movie->image,
            'categories' => $categories,
            'marks' => $marks,
            'directors' => $directors,
            'actors' => $actors
        ];
    }







    /*$movie = Movie::where('id', '=', $params_array['id'])->get(); 

            $movie->actors_movies()->attach($categories);

            array_push($actors, $actor);

            $data = [
                'code' => 200,
                'status' => 'success',
                'movie' => $movie
            ];

            $dataResponse = [
                'code' => 200,
                'status' => 'success',
                'movie' => $data
            ];
            
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Movie not found'
            ];
        }
        return response()->json($data);*/
}
