<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function get_all_categories()
    {
        return response()->json([
            'error' => false,
            'data' => Category::all()
        ], 200);
    }

    public function get_detail_category($id){
        try{
            $category = Category::with('classroom')->where('id', $id)->firstOrFail();
            return response()->json([
                'error' => false,
                'data' => $category
            ], 200);
        }catch(\Exception $ex){
            return response()->json([
                'error' => true,
                'message' => 'data not found!'
            ], 404);
        }
    }
}
