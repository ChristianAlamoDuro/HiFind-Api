<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Director;


class DirectorController extends Controller
{
    public function build_show_response($director)
    {
        return [
            'code' => 200,
            'status' => 'succes',
            'director' => [
                'name' => $director->name,
                'surname' => $director->surname, 
                'birthday' => $director->birthday, 
                'biography' => $director->biography, 
                'image' => $director->image
            ]
        ];
    }

    public function index()
    {
        $data = [];
        $directors = Director::all();
        foreach ($directors as $director) {
            array_push($data, $this->build_show_response($director));
        }
        return response()->json([
            'code' => 200,
            'status' => 'succes index',
            'directors' => $data
        ]);
    }
   
    public function show($info)
    {
      
        $words = explode("-", $info);
        $data = [];
        $bool = false;

        foreach ($words as $word) {
       
        $directorsByName = Director::where('name', 'like', '%' . $word . '%')->get();
           
            if (count($directorsByName)>0){
                $bool = true;
                foreach ($directorsByName as $director) { 
                    array_push($data, $this->build_show_response($director));
                }   
            }
           

        $directorsBySurname = Director::where('surname', 'like', '%' . $word . '%')->get();

            if (count($directorsBySurname)>0){
                $bool = true;
                foreach ($directorsBySurname as $director) { 
                array_push($data, $this->build_show_response($director));
                }
            }
            
        }
        
        if ($bool == false){
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'director not found'
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
                    $director = new Director();
                    $director->name = $params_array['name'];
                    $director->surname = $params_array['surname'];
                    $director->birthday = $params_array['birthday'];
                    $director->biography = $params_array['biography'];
                    $director->image = $params_array['image'];
                    $director->save();
    
                    $data = [
                        'code' => 200,
                        'status' => 'succes',
                        'message' => 'director stored successfully',
                        'director' => $director
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
                    $director = Director::where('id', $id)->update($params_update);
                    $data = [
                        'code' => 200,
                        'status' => 'succes',
                        'message' => 'director updated successfully',
                        'director' => $params_array
                    ];
                }
                
            }
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Error. director could not be stored.'
            ];
        }
        return response()->json($data);
    }

}
