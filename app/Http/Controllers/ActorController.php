<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Actor;
use App\Movie;
use App\Validation;


class ActorController extends Controller
{
    public function build_show_response($actor)
    {
        return [
            'name' => $actor->name,
            'surname' => $actor->surname, 
            'birthday' => $actor->birthday, 
            'image' => $actor->image,
            'biography' => $actor->biography
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
            'status' => 'succes',
            'actors' => $data
        ];
        $data = [
            'code' => 200,
            'status' => 'succes',
        ];

        return response()->json($dataResponse);
    }
   
    public function show($info)
    {
      
        $words = explode("-", $info);
        $data = [];
        $bool = false;

        if (!is_null($words)){
            foreach ($words as $word) {
       
                $actorsByName = Actor::where('name', 'like', '%' . $word . '%')->get();
                   
                    if (count($actorsByName)>0){
                        $bool = true;
                        foreach ($actorsByName as $actor) { 
                            array_push($data, $this->build_show_response($actor));
                        }   
                    }
                   
        
                $actorsBySurname = Actor::where('surname', 'like', '%' . $word . '%')->get();
        
                    if (count($actorsBySurname)>0){
                        $bool = true;
                        foreach ($actorsBySurname as $actor) { 
                        array_push($data, $this->build_show_response($actor));
                        }
                    }
                    
                }
                
                if ($bool == false){
                    $dataResponse = [
                        'code' => 200,
                        'status' => 'success',
                        'actors' => $data
                    ];
                }
        }
        else {
            $dataResponse = [
                'code' => 404,
                'status' => 'error',
                'message' => 'actor not found'
            ];
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

            $validate = \Validator::make($params_array, [
                'name' => 'required',
                'surname' => 'required',
                'birthday' => 'required',
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
                    $data = $this->prepare_update($params_array);
                } else {
                    $data = $this->prepare_store($params_array);
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


    public function prepare_update($params_array)
    {
        $params_to_update = [
            'name' => $params_array['name'],
            'surname' => $params_array['surname'],
            'birthday' => $params_array['birthday'],
            'biography' => $params_array['biography'],
            'image' => $params_array['image']
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


    public function prepare_store($params_array)
    {
        $actor = new Actor();
        $actor->name = $params_array['name'];
        $actor->surname = $params_array['surname'];
        $actor->birthday = $params_array['birthday'];
        $actor->biography = $params_array['biography'];
        $actor->image = $params_array['image'];
        $actor->save();

        $movies = [];

        foreach ($params_array['movies'] as $movie) {

            if (Movie::find($movie)) {
                array_push($movies, $movie);
            }

        }
        $actor->movies()->attach($movies);
        return  [
            'code' => 200,
            'status' => 'success',
            'message' => 'actor stored successfull',
            'actor' => $actor
        ];
    }

}
