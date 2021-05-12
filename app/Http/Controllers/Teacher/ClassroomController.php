<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function get_classroom()
    {
        $user_id = Auth::user()->id;
        try{
            $data = Classroom::with('category')->where('user_id', $user_id)->get();
            return ResponseHelper::responseSuccessWithData($data);
        }catch(\Exception $ex){
            return ResponseHelper::responseError($ex, 500);
        }
    }

    public function get_detail_class($id)
    {
        try{
            $classroom = Classroom::with('topics', 'category', 'members', 'members.user','user')->findOrFail($id);
            return ResponseHelper::responseSuccessWithData($classroom);
        }catch(\Exception $ex){
            return ResponseHelper::responseError('Data not found!', 404);
        }
    }
}
