<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Movie;


class MovieController extends Controller
{
 
    public function build_show_response($movie)
    {
        $categories = [];
        $marks = [];
        foreach ($movie->categories_movies as $category) {
            array_push($categories, $category->name);
        }
        foreach ($movie->marks_movies as $mark) {
            array_push($marks, $mark->pivot->mark);
        }
        
        return [
            'code' => 200,
            'status' => 'succes',
            'movie' => [
                'title' => $movie->title,
                'out_date' => $movie->out_date, 
                'public_directed' => $movie->public_directed, 
                'film_producer' => $movie->film_producer, 
                'duration' => $movie->duration,
                'sinopsis' => $movie->sinopsis,
                'image' => $movie->image,
                'categories' => $categories,
                'marks' => $marks
            ]
        ];
    }

    public function index()
    {
        $data = [];
        $movies = Movie::all();
        foreach ($movies as $movie) {
            array_push($data, $this->build_show_response($movie));
        }
        return response()->json([
            'code' => 200,
            'status' => 'succes',
            'movies' => $data
        ]);
    }
    
    public function show($title)
    {
        $movies = Movie::where('title', 'like', '%' . $title . '%')->get();
        
        $data = [];
        if (!is_null($movies)) {
            foreach ($movies as $movie) {
                array_push($data, $this->build_show_response($movie));
            }
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'movie not found'
            ];
        }
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $json = $request->input('json', null);

        if ($json) {
            $params_array = json_decode($json, true);
            $validate = \Validator::make($params_array, [
                'title' => 'required',
                'out_date' => 'required',
                'public_directed' => 'required',
                'film_producer' => 'required',
                'duration' => 'required',
                'sinopsis' => 'required',
                'image' => 'required'
            ]);

            if ($validate->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'Error',
                    'message' => 'The data validation was not correct'
                ];
            } else {
                if (!isset($params_array['id'])) {
                    
                    $movie = new Movie();
                    $movie->title = $params_array['title'];
                    $movie->out_date = $params_array['out_date'];
                    $movie->public_directed = $params_array['public_directed'];
                    $movie->film_producer = $params_array['film_producer'];
                    $movie->duration = $params_array['duration'];
                    $movie->sinopsis = $params_array['sinopsis'];
                    $movie->image = $params_array['image'];
                    $movie->save();
    
                    $categories = [];
                    $directors = [];
                    $actors = [];
    
                    /* Categories */
                    foreach ($params_array['categories'] as $category) {
                        array_push($categories, $category);
                    }
                    $movie->categories_movies()->attach($categories);
    
                    /* Directors */
                    foreach ($params_array['directors'] as $director) {
                        array_push($directors, $director);
                    }
                    $movie->directors_movies()->attach($directors);
    
                    /* Actors */
                    foreach ($params_array['actors'] as $actor) {
                        array_push($actors, $actor);
                    }
                    $movie->actors_movies()->attach($actors);
                    
    
                    $data = [
                        'code' => 200,
                        'status' => 'succes',
                        'message' => 'Movie stored successfully',
                        'movie' => $movie
                    ];
                }
                else {
                    $params_update = [
                        'title' => $params_array['title'],
                        'out_date' => $params_array['out_date'],
                        'public_directed' => $params_array['public_directed'],
                        'film_producer' => $params_array['film_producer'],
                        'duration' => $params_array['duration'],
                        'sinopsis' => $params_array['sinopsis'],
                        'image' => $params_array['image']
                    ];
                    $id = $params_array['id'];
                    unset($params_array['id']);
                    unset($params_array['created_at']);

                    $movie = Movie::where('id', $id)->update($params_update);
                    $movie = Movie::find($id);
                    $categories = [];
                    foreach ($params_array['categories'] as $category) {
                        array_push($categories, $category);
                    }
                    
                    $movie->categories()->sync($categories);

                    $data = [
                        'code' => 200,
                        'status' => 'succes',
                        'message' => 'movie updated successfully',
                        'movie' => $params_array
                    ];
                }
               
            }
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Error. Movie could not be stored.'
            ];
        }
        return response()->json($data);
    }

    /*public function update($id, Request $request)
    {
        $json = $request->input('json', null);

        if ($json) {
            
            $params_array = json_decode($json, true);
            
            $validate = \Validator::make($params_array, [
                'title' => 'required',
                'out_date' => 'required',
                'public_directed' => 'required',
                'film_producer' => 'required',
                'duration' => 'required',
                'sinopsis' => 'required',
                'image' => 'required'
            ]);

            
            if ($validate->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'succes',
                    'message' => 'The data validation was not correct'
                ];
            } else {
                unset($params_array['id']);
                unset($params_array['created_at']);

                $movie = Movie::where('id', $id)->update($params_array);
                $data = [
                    'code' => 200,
                    'status' => 'succes',
                    'message' => 'Movie updated successfully.',
                    'movie' => $params_array
                ];
            }
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Error. Movie could not be stored.'
            ];
        }
        return response()->json($data);
    } */


}
