<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Actor;
use App\Category;
use App\Director;
use App\Movie;

class SelectactorController extends Controller
{
    public function show($id)
    {

        $actorSelected = actor::find($id);
        $data = [];
        if (!is_null($actorSelected)) {

            $actors = actor::all();

            foreach ($actors as $actor) {
                array_push($data, $this->build_show_response($actor));
                break;
            }

            $dataResponse = [
                'code' => 200,
                'status' => 'success',
                'actors' => $data
            ];
        } else {
            $dataResponse = [
                'code' => 404,
                'status' => 'error',
                'message' => 'actor not found'
            ];
        }
        return response()->json($dataResponse);
    }

    public function build_show_response($actor)
    {
        $categories = [];
        $directors = [];
        $actors = [];
        $marks = [];

        foreach ($actor->categories_actors as $category) {
            array_push($categories, $category->name);
        }

        foreach ($actor->marks_actors as $mark) {
            array_push($marks, $mark->pivot->mark);
        }
        foreach ($actor->directors_actors as $director) {
            array_push($directors, $director->name);
        }

        foreach ($actor->actors_actors as $actor) {
            array_push($actors, $actor->name);
        }

        return [
            'id' => $actor->id,
            'title' => $actor->title,
            'out_date' => $actor->out_date,
            'public_directed' => $actor->public_directed,
            'film_producer' => $actor->film_producer,
            'duration' => $actor->duration,
            'sinopsis' => $actor->sinopsis,
            'image' => $actor->image,
            'categories' => $categories,
            'marks' => $marks,
            'directors' => $directors,
            'actors' => $actors
        ];
    }

}
