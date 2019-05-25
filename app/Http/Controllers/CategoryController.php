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

        return response()->json([
            'code' => 200,
            'status' => 'succes',
            'categories' => $categories
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
                'name' => 'required'
            ]);

            if ($validate->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'succes',
                    'message' => 'Error de validación no se ha guardado la categoría'
                ];
            } else {
                $category = new Category();
                $category->name = $params_array['name'];
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
                'name' => 'required'
            ]);

            if ($validate->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'succes',
                    'message' => 'Error de validación no se ha acoualizadp la categoría'
                ];
            } else {
                unset($params_array['id']);
                unset($params_array['created_at']);

                $category = Category::where('id',$id)->update($params_array);
                $data = [
                    'code' => 200,
                    'status' => 'succes',
                    'message' => 'Categoría actualizada satisfactoriamente',
                    'category' => $params_array
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
