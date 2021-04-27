<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Helpers\ResponseHelper;

class CategoryController extends Controller
{
    public function get_all_categories()
    {
        try{
            $data = Category::all();
            return ResponseHelper::responseSuccessWithData($data);
        }catch(\Exception $ex){
            return ResponseHelper::responseError($ex, 500);
        }
        
    }

    public function get_detail_category($id){
        try{
            $category = Category::with('classroom')->where('id', $id)->firstOrFail();
            return ResponseHelper::responseSuccessWithData($category);
        }catch(\Exception $ex){
            return ResponseHelper::responseError('Data not found!', 404);
        }
    }
}
