<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Helpers\ResponseHelper;

class CategoryController extends Controller
{
    public function get_category()
    {
        $categories = Category::all();
        return ResponseHelper::responseSuccessWithData($categories);
    }
}
