<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Movie;
use App\Category;
use App\Actor;
use App\Director;

class CategoryMovieController extends Controller
{

    public function show($name)
    {
        $movie_data = [];
        if (is_numeric($name)) {
            $category = Category::find($name);
            foreach ($category->movies as $movie) {
                array_push($movie_data, $this->build_show_response($movie));
            }
        } else {
            $categories = Category::where('name', $name)->get();
            if (sizeof($categories) != 0) {
                $movie_data = $this->build_movie_response($categories);
            }
        }
        if (!empty($movie_data)) {
            $data = [
                'code' => 200,
                'status' => 'succes',
                'movies' => $movie_data
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'there is no movie for that category'
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
        $directors = [];
        $actors = [];
        $marks = [];

        foreach ($movie->categories_movies as $category) {
            array_push($categories, $category->name);
        }
        foreach ($movie->marks_movies as $mark) {
            array_push($marks, $mark->pivot->mark);
        }

        if (sizeof($marks) > 0) {
            $marks = $this->array_half($marks);
        } else {
            $marks = 0;
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
