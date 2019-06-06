<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Illuminate\Http\response;
use App\Game;
use Illuminate\Support\Facades\DB;

class DeleteCategoryController extends Controller
{
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        if (Category::find($params_array['id'])) {
            $cagegory_to_delete = Category::find($params_array['id'])->delete();
            $data = [
                'code' => 200,
                'status' => 'succes',
                'message' => 'Category deleted'
            ];
        } else {
            $data = [
                'code' => 400,
                'status' => 'succes',
                'message' => 'Category not found'
            ];
        }
        return response()->json($data);
    }
}
