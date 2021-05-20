<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use App\Models\ClassMember;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function get_dashboard_content(){
        $user_id = Auth::user()->id;
        try{
            $classroom = Classroom::where('user_id', $user_id)->get();
            $class_members = ClassMember::with('classroom')->whereHas('classroom', function($q) use($user_id){
                $q->where('user_id', $user_id);
            })->get();

            $data = [
                'total_classroom' => $classroom->count(),
                'total_members' => $class_members->count(),
            ];

            return ResponseHelper::responseSuccessWithData($data);
        }catch(\Exception $ex){
            return ResponseHelper::responseError($ex, 500);
        }
    }
}
