<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;

class ClassroomController extends Controller
{
    public function get_all_classroom(){
        try{
            return response()->json([
                'error' => false,
                'data' => Classroom::with('category')->get()
            ], 200);
        }catch(\Exception $ex){
            return response()->json([
                'error' => true,
                'message' => 'Error!'
            ], 500);
        }
    }   

    public function get_detail_classroom($id)
    {
        try{
            $classroom = Classroom::with('topics', 'category', 'members', 'members.user','user')->findOrFail($id);
            return response()->json([
                'error' => false,
                'data' => $classroom
            ], 200);
        }catch(\Exception $ex){
            return response()->json([
                'error' => false,
                'message' => 'Data not found!'
            ], 400);
        }
    }
}
