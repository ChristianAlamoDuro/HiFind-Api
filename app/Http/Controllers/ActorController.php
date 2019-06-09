<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Actor;


class ActorController extends Controller
{
    public function build_show_response($actor)
    {
        return [
            'code' => 200,
            'status' => 'succes',
            'actor' => [
                'name' => $actor->name,
                'surname' => $actor->surname, 
                'birthday' => $actor->birthday, 
                'biography' => $actor->biography, 
                'image' => $actor->image
            ]
        ];
    }

    public function index()
    {
        $data = [];
        $actors = Actor::all();
        foreach ($actors as $actor) {
            array_push($data, $this->build_show_response($actor));
        }
        return response()->json([
            'code' => 200,
            'status' => 'succes index',
            'actors' => $data
        ]);
    }
   
    public function show($info)
    {
      
        $words = explode("-", $info);
        $data = [];
        $bool = false;

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
                $data = [
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

        if ($json) {
            $params_array = json_decode($json, true);
           
            $validate = \Validator::make($params_array, [
                'name' => 'required',
                'surname' => 'required',
                'birthday' => 'required',
                'biography' => 'required',
                'image' => 'required'
            ]);

            if ($validate->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'Error',
                    'message' => 'The data validation was not correct'
                ];
            } else {
                if (!isset($params_array['id'])){
                    $actor = new Actor();
                    $actor->name = $params_array['name'];
                    $actor->surname = $params_array['surname'];
                    $actor->birthday = $params_array['birthday'];
                    $actor->biography = $params_array['biography'];
                    $actor->image = $params_array['image'];
                    $actor->save();
    
                    $data = [
                        'code' => 200,
                        'status' => 'succes',
                        'message' => 'actor stored successfully',
                        'actor' => $actor
                    ];
                }

                else {$params_update = [
                    'name' => $params_array['name'],
                    'surname' => $params_array['surname'],
                    'birthday' => $params_array['birthday'],
                    'biography' => $params_array['biography'],
                    'image' => $params_array['image']
                ];
                    $id = $params_array['id'];
                    $actor = Actor::where('id', $id)->update($params_update);
                    $data = [
                        'code' => 200,
                        'status' => 'succes',
                        'message' => 'actor updated successfully',
                        'actor' => $params_array
                    ];
                }
                
            }
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Error. actor could not be stored.'
            ];
        }
        return response()->json($data);
    }

}
