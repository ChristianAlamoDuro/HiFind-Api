<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\response;
use App\Category;
use App\Validation;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $categories_special = [];
        foreach ($categories as $category) {
            array_push($categories_special, $this->prepare_category($category));
        } 
        return response()->json([
            'code' => 200,
            'status' => 'succes',
            'categories' => $categories_special
        ]);
    }

    public function show($id)
    {

        if ($id == 'is_game' || $id == 'is_movie' || $id == 'is_special_movie' || $id == 'is_special_game') {
            $categories = Category::where($id, 1)->get();
            $categories_special = [];

            foreach ($categories as $category) {
                array_push($categories_special, ['id' => $category->id, 'name' => $category->name]);
            }
        } elseif (Category::find($id)) {
            $category = Category::find($id);
            $categories_special = [];
            array_push($categories_special, $this->prepare_category($category));
        }
        if (isset($category)) {
          
            $data = [
                'code' => 200,
                'status' => 'succes',
                'category' => $categories_special
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

        if (is_object(json_decode($json))) {
            $user_id = json_decode($json)->user_id;
            if (Validation::adminValidate($user_id)) {
                $params_array = json_decode($json, true);
                $category = Category::where('name', 'like', '%' . $params_array['name'] . '%')->where($params_array['special_category'], 1)->get();
                if (!empty($category)) {
                    $validate = \Validator::make($params_array, [
                        'name' => 'required',
                        'special_category' => 'required'
                    ]);
                    if ($validate->fails()) {
                        $data = [
                            'code' => 400,
                            'status' => 'Error',
                            'message' => 'Validation error'
                        ];
                    } else {
                        if (isset($params_array['id'])) {
                            if (Category::find($params_array['id']) != NULL) {
                                $data = $this->prepare_update($params_array);
                            }else{
                                $data = [
                                    'code' => 404,
                                    'status' => 'error',
                                    'message' => 'Category not found to update'
                                ];
                            }
                        } else {
                            $data = $this->prepare_store($params_array);
                        }
                    }
                }
            } else {
                $data = [
                    'code' => 404,
                    'status' => 'Error',
                    'message' => 'Error this user role dont have permission'
                ];
            }
        } else {
            $data = [
                'code' => 404,
                'status' => 'Error',
                'message' => 'Wrong data value'
            ];
        }
        return response()->json($data);
    }

    public function prepare_update($params_array)
    {
        $id = $params_array['id'];
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
        return [
            'code' => 200,
            'status' => 'succes',
            'message' => 'Categoría actualizada satisfactoriamente',
            'category' => $params_to_update
        ];
    }

    public function prepare_store($params_array)
    {
        $special_category = $params_array['special_category'];
        $category = new Category();
        $category->name = $params_array['name'];
        $category->$special_category = 1;
        $category->save();
        return [
            'code' => 200,
            'status' => 'succes',
            'message' => 'Categoría guardada satisfactoriamente',
            'category' => $category
        ];
    }

    public function prepare_category($category)
    {
        if ($category->is_game == 1) {
            return ['id' => $category->id, 'name' => $category->name, 'special_category' => 'is_game'];
        }
        if ($category->is_movie == 1) {
            return ['id' => $category->id, 'name' => $category->name, 'special_category' => 'is_movie'];
        }
        if ($category->is_special_game == 1) {
            return ['id' => $category->id, 'name' => $category->name, 'special_category' => 'is_special_game'];
        }
        if ($category->is_special_movie == 1) {
            return ['id' => $category->id, 'name' => $category->name, 'special_category' => 'is_special_movie'];
        }
    }
}
