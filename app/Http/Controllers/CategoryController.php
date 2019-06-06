<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\response;
use App\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $categories_special = [];
        foreach ($categories as $value) {
            if ($value->is_game == 1) {
                array_push($categories_special, ['id'=>$value->id, 'name' => $value->name, 'special_category' => 'is_game']);
            }
            if ($value->is_movie == 1) {
                array_push($categories_special, ['id'=>$value->id, 'name' => $value->name, 'special_category' => 'is_movie']);
            }
            if ($value->is_special_game == 1) {
                array_push($categories_special, ['id'=>$value->id, 'name' => $value->name, 'special_category' => 'is_special_game']);
            }
            if ($value->is_special_movie == 1) {
                array_push($categories_special, ['id'=>$value->id, 'name' => $value->name, 'special_category' => 'is_special_movie']);
            }
        }
        return response()->json([
            'code' => 200,
            'status' => 'succes',
            'categories' => $categories_special
        ]);
    }

    public function show($id)
    {
        $category = Category::find($id);

        if (is_object($category)) {
            $data = [
                'code' => 200,
                'status' => 'succes',
                'category' => $category
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'category not found'
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
                'special_category' => 'required'
            ]);

            if ($validate->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'succes',
                    'message' => 'Error de validación no se ha guardado la categoría'
                ];
            } else {
                $special_category = $params_array['special_category'];
                $category = new Category();
                $category->name = $params_array['name'];
                $category->$special_category = 1;
                $category->save();
                $data = [
                    'code' => 200,
                    'status' => 'succes',
                    'message' => 'Categoría guardada satisfactoriamente',
                    'category' => $category
                ];
            }
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Ups un error inesperado disculpe las molestias'
            ];
        }
        return response()->json($data);
    }

    public function update($id, Request $request)
    {
        $json = $request->input('json', null);

        if ($json) {
            $params_array = json_decode($json, true);

            $validate = \Validator::make($params_array, [
                'name' => 'required',
                'special_category' => 'required'
            ]);

            if ($validate->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'succes',
                    'message' => 'Error de validación no se ha actualizado la categoría'
                ];
            } else {
                unset($params_array['id']);
                unset($params_array['created_at']);
                $params_to_update = [
                    'is_game' => false,
                    'is_movie' => false,
                    'is_special_game' => false,
                    'is_special_movie' => false
                ];

                Category::where('id', $id)->update($params_to_update);
                $params_to_update = [
                    'name' => $params_array['name'],
                    $params_array['special_category'] => true
                ];
                Category::where('id', $id)->update($params_to_update);
                $data = [
                    'code' => 200,
                    'status' => 'succes',
                    'message' => 'Categoría actualizada satisfactoriamente',
                    'category' => $params_to_update
                ];
            }
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'Ups un error inesperado disculpe las molestias'
            ];
        }
        return response()->json($data);
    }
}
