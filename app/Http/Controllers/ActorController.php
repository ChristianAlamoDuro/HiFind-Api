<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Actor;
use App\Movie;
use App\Validation;


class ActorController extends Controller
{
    public function build_show_response($actor)
    {
        $movies = $actor->movies;
        return [
            'id' => $actor->id,
            'name' => $actor->name,
            'surname' => $actor->surname,
            'birthday' => $actor->birthday,
            'image' => $actor->image,
            'biography' => $actor->biography,
            'movies' => $movies
        ];
    }

    public function index()
    {
        $data = [];
        $actors = Actor::all();
        foreach ($actors as $actor) {
            array_push($data, $this->build_show_response($actor));
        }
        $dataResponse = [
            'code' => 200,
            'status' => 'success',
            'actors' => $data
        ];
        $data = [
            'code' => 200,
            'status' => 'success',
        ];

        return response()->json($dataResponse);
    }

    public function show($info)
    {

        $words = explode("_", $info);
        $data = [];
        $bool = false;
        $idsInsertados = [];


        if (!is_numeric($info)) {
            if (!is_null($words)) {
                foreach ($words as $word) {
                    $actorsByName = Actor::where('name', 'like', '%' . $word . '%')->get();

                    if (count($actorsByName) > 0) {
                        $bool = true;
                        foreach ($actorsByName as $actor) {
                           
                            if (!in_array($actor->id, $idsInsertados)){
                                array_push($data, $this->build_show_response($actor));
                                array_push($idsInsertados, $actor->id);
                             }
                        }
                      
                    }

                    $actorsBySurname = Actor::where('surname', 'like', '%' . $word . '%')->get();

                    if (count($actorsBySurname) > 0) {
                        $bool = true;
                        foreach ($actorsBySurname as $actor) {

                            if (!in_array($actor->id, $idsInsertados)){
                                array_push($data, $this->build_show_response($actor));
                                array_push($idsInsertados, $actor->id);
                             }

                        }
                    }
                }

                if ($bool == true) {
                    $dataResponse = [
                        'code' => 200,
                        'status' => 'success',
                        'actors' => $data
                    ];
                }
                else{
                    $data = [
                        'code' => 404,
                        'status' => 'error',
                        'message' => 'actor not found'
                    ];
                }
            } else {
                $dataResponse = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'No words entered or incorrect format'
                ];
            }
        } else {
            $actor = Actor::find($info);
            array_push($data, $this->build_show_response($actor));
            $data = array_unique($data);
        }


        return response()->json($data);
    }

    public function store(Request $request)
    {
        $json = $request->input('json', null);

        if (is_object(json_decode($json))) {

            $user_id = json_decode($json)->user_id;

            if (Validation::adminValidate($user_id)) {
                
                $params_array = json_decode($json, true);
                $image = $request->file('image');
                $extension = $image->getClientOriginalExtension();

                Storage::disk('uploads')->put($image->getFilename() . '.' . $extension,  File::get($image));
               
                $image_name = "/public/storage/img/".$image->getFilename() . '.' . $extension; 


            $validate = \Validator::make($params_array, [
                'name' => 'required',
                'surname' => 'required',
                'birthday' => 'required|date_format:d/m/Y',
                'biography' => 'required'
            ]);

            if ($validate->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'Error',
                    'message' => 'Data validation was not correct'
                ];
            } else {
                
                if (isset($params_array['id'])) {
                    $data = $this->prepare_update($params_array, $image_name);
                } else {
                    $data = $this->prepare_store($params_array, $image_name);
                }

                }
            } else {
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'Error this user role doesnt have permission'
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
            'name' => $params_array['name'],
            'surname' => $params_array['surname'],
            'birthday' => $params_array['birthday'],
            'biography' => $params_array['biography'],
            'image' => $image_name
        ];

        $id = $params_array['id'];

        unset($params_array['id']);
        unset($params_array['created_at']);

        $actor = Actor::where('id', $id)->update($params_to_update);
        $actor = Actor::find($id);

        $movies = [];

        foreach ($params_array['movies'] as $movie) {
            if (Movie::find($movie)) {
                array_push($movies, $movie);
            }
        }
        $actor->movies()->sync($movies);
        return [
            'code' => 200,
            'status' => 'success',
            'message' => 'actor updated successfull',
            'actor' => $params_array
        ];
    }


    public function prepare_store($params_array, $image_name)
    {
        $actor = new Actor();
        $actor->name = $params_array['name'];
        $actor->surname = $params_array['surname'];
        $actor->birthday = $params_array['birthday'];
        $actor->biography = $params_array['biography'];
        $actor->image = $image_name;
        $actor->save();

        return  [
            'code' => 200,
            'status' => 'success',
            'message' => 'actor stored successfull',
            'actor' => $actor
        ];
    }
}
