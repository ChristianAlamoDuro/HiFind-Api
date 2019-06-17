<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Movie;
use App\Category;
use App\Validation;
use App\Director;
use App\Actor;


class MovieController extends Controller
{

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

    public function index()
    {
        $data = [];
        $movies = Movie::all();

        foreach ($movies as $movie) {
            array_push($data, $this->build_show_response($movie));
        }
        
        $collection = collect($data);
        $data = $collection->sortBy('name');
        $data = $data->values()->all();

        $dataResponse = [
            'code' => 200,
            'status' => 'success',
            'movies' => $data
        ];
        return response()->json($dataResponse);
    }

    public function show($title)
    {
        $data = [];
        if (!is_numeric($title)) {
            $movies = Movie::where('title', 'like', '%' . $title . '%')->get();
            foreach ($movies as $movie) {
                array_push($data, $this->build_show_response($movie));
            }
        } else {
            $movies = Movie::find($title);
            array_push($data, $this->build_show_response($movies));
        }
        if (!is_null($movies)) {
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

    public function store(Request $request)
    {

        $json = $request->input('json', null);

        if (is_object(json_decode($json))) {

            $user_id = json_decode($json)->user_id;

            if (Validation::adminValidate($user_id)) {

                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();

                Storage::disk('uploads')->put($image->getFilename() . '.' . $extension,  File::get($image));

                $image_name = "/public/storage/img/" . $image->getFilename() . '.' . $extension;
                $params_array = json_decode($json, true);

                $validate = \Validator::make($params_array, [
                    'title' => 'required',
                    'out_date' => 'required|date_format:d/m/Y',
                    'public_directed' => 'required',
                    'film_producer' => 'required',
                    'duration' => 'required',
                    'sinopsis' => 'required'
                ]);

                if ($validate->fails()) {
                    $data = [
                        'code' => 400,
                        'status' => 'success',
                        'message' => 'Validation error'
                    ];
                } else {
                    if (isset($params_array['id'])) {
                        if (Movie::find($params_array['id']) != NULL) {
                            $data = $this->prepare_update($params_array, $image_name);
                        } else {
                            $data = [
                                'code' => 404,
                                'status' => 'error',
                                'message' => 'Movie not found to update'
                            ];
                        }
                    } else {
                        $data = $this->prepare_store($params_array, $image_name);
                    }
                }
            } else {
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Error this user role dont have permission'
                ];
            }
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Wrong data values'
            ];
        }
        return response()->json($data);
    }

    public function prepare_update($params_array, $image_name)
    {
        $params_to_update = [
            'title' => ucfirst($params_array['title']),
            'sinopsis' => $params_array['sinopsis'],
            'out_date' => $params_array['out_date'],
            'public_directed' => $params_array['public_directed'],
            'duration' => $params_array['duration'],
            'film_producer' => $params_array['film_producer'],
            'image' => $image_name
        ];
        $id = $params_array['id'];
        unset($params_array['id']);
        unset($params_array['created_at']);

        $movie = Movie::where('id', $id)->update($params_to_update);
        $movie = Movie::find($id);

        $categories = [];
        $directors = [];
        $actors = [];

        foreach ($params_array['categories'] as $category) {
            if (Category::find($category)) {
                array_push($categories, $category);
            }
        }
        $movie->categories_movies()->sync($categories);

        foreach ($params_array['directors'] as $director) {
            if (Director::find($director)) {
                array_push($directors, $director);
            }
        }
        $movie->directors_movies()->sync($directors);

        foreach ($params_array['actors'] as $actor) {
            if (Actor::find($actor)) {
                array_push($actors, $actor);
            }
        }
        $movie->actors_movies()->sync($actors);

        return [
            'code' => 200,
            'status' => 'success',
            'message' => 'movie updated successfully',
            'movie' => $params_array
        ];
    }



    public function prepare_store($params_array, $image_name)
    {
        $movie = new Movie();
        $movie->title = ucfirst($params_array['title']);
        $movie->duration = $params_array['duration'];
        $movie->film_producer = $params_array['film_producer'];
        $movie->sinopsis = $params_array['sinopsis'];
        $movie->out_date = $params_array['out_date'];
        $movie->public_directed = $params_array['public_directed'];
        $movie->image = $image_name;
        $movie->save();

        $categories = [];
        $directors = [];
        $actors = [];

        foreach ($params_array['categories'] as $category) {
            if (Category::find($category)) {
                array_push($categories, $category);
            }
        }
        $movie->categories_movies()->attach($categories);

        foreach ($params_array['directors'] as $director) {
            if (Director::find($director)) {
                array_push($directors, $director);
            }
        }
        $movie->directors_movies()->attach($directors);


        foreach ($params_array['actors'] as $actor) {
            if (Actor::find($actor)) {
                array_push($actors, $actor);
            }
        }
        $movie->actors_movies()->attach($actors);


        return  [
            'code' => 200,
            'status' => 'success',
            'message' => 'movie store successfull',
            'movie' => $movie
        ];
    }
}
