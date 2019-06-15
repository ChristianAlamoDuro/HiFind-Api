<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Director;
use App\Movie;
use App\Validation;


class DirectorController extends Controller
{
    public function build_show_response($director)
    {
        $movies = $director->movies;

        return [
            'id' => $director->id,
            'name' => $director->name,
            'surname' => $director->surname,
            'birthday' => $director->birthday,
            'image' => $director->image,
            'biography' => $director->biography,
            'movies' => $movies
        ];
    }

    public function index()
    {
        $data = [];
        $directors = Director::all();
        foreach ($directors as $director) {
            array_push($data, $this->build_show_response($director));
        }
        $collection = collect($data);
        $data = $collection->sortBy('name');
        $data = $data->values()->all();
        $dataResponse = [
            'code' => 200,
            'status' => 'success',
            'directors' => $data
        ];

        return response()->json($dataResponse);
    }

    public function show($info)
    {

        $words = explode("_", $info);
        $data = [];
        $idsInsertados = [];

        if (!is_numeric($info)) {

            if (!is_null($words)) {

                foreach ($words as $word) {

                    $directorsByName = Director::where('name', 'like', '%' . $word . '%')->get();
                    $directorsBySurname = Director::where('surname', 'like', '%' . $word . '%')->get();


                    if (count($directorsByName) > 0) {

                        $bool = true;
                        foreach ($directorsByName as $director) {

                            if (!in_array($director->id, $idsInsertados)) {
                                array_push($data, $this->build_show_response($director));
                                array_push($idsInsertados, $director->id);
                            }

                            //var_dump($idsInsertados); die();
                        }
                    }

                    if (count($directorsBySurname) > 0) {

                        $bool = true;
                        foreach ($directorsBySurname as $director) {

                            // var_dump($director->id, $idsInsertados); die();
                            if (!in_array($director->id, $idsInsertados)) {
                                array_push($data, $this->build_show_response($director));
                                array_push($idsInsertados, $director->id);
                            }
                        }
                    }
                }


                if (!empty($data)) {
                    $dataResponse = [
                        'code' => 200,
                        'status' => 'success',
                        'directors' => $data
                    ];
                } else {
                    $dataResponse = [
                        'code' => 404,
                        'status' => 'error',
                        'message' => 'Director not found'
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
            $director = Director::find($info);
            $dataResponse = array_push($data, $this->build_show_response($director));
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
                    'name' => 'required',
                    'surname' => 'required',
                    'birthday' => 'required|date_format:d/m/Y',
                    'biography' => 'required'
                ]);

                if ($validate->fails()) {
                    $data = [
                        'code' => 400,
                        'status' => 'error',
                        'message' => 'Data validation was not correct'
                    ];
                } else {

                    if (isset($params_array['id'])) {
                        if (Director::find($params_array['id']) != NULL) {
                            $data = $this->prepare_update($params_array, $image_name);
                        } else {
                            $data = [
                                'code' => 404,
                                'status' => 'error',
                                'message' => 'Director not found to update'
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
            'name' => ucfirst($params_array['name']),
            'surname' => $params_array['surname'],
            'birthday' => $params_array['birthday'],
            'biography' => $params_array['biography'],
            'image' => $image_name
        ];

        $id = $params_array['id'];

        unset($params_array['id']);
        unset($params_array['created_at']);

        $director = Director::where('id', $id)->update($params_to_update);
        $director = Director::find($id);

        $movies = [];

        foreach ($params_array['movies'] as $movie) {
            if (Movie::find($movie)) {
                array_push($movies, $movie);
            }
        }
        $director->movies()->sync($movies);
        return [
            'code' => 200,
            'status' => 'success',
            'message' => 'director updated successfull',
            'director' => $params_array
        ];
    }


    public function prepare_store($params_array, $image_name)
    {
        $director = new Director();
        $director->name = ucfirst($params_array['name']);
        $director->surname = $params_array['surname'];
        $director->birthday = $params_array['birthday'];
        $director->biography = $params_array['biography'];
        $director->image = $image_name;
        $director->save();

        return  [
            'code' => 200,
            'status' => 'success',
            'message' => 'director stored successfull',
            'director' => $director
        ];
    }
}
