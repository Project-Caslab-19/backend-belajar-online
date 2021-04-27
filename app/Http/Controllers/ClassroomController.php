<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;

class ClassroomController extends Controller
{
    public function get_all_classroom(){
        return response()->json([
            'error' => false,
            'data' => Classroom::with('category')->get()
        ]);
    }   

    public function get_detail_classroom($id)
    {
        return $classroom = Classroom::with('topics', 'category', 'members', 'members.user','user')->findOrFail($id);

    }
}
