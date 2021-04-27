<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\ClassMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClassroomController extends Controller
{
    public function get_all_classroom(){
        return response()->json([
            'error' => false,
            'data' => Classroom::with('category')->get()
        ], 200);
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

    public function enroll_classroom(Request $request)
    {
        $user_id = Auth::user()->id;
        $class_id = $request->class_id;
        $token = $request->token;

        $validator = Validator::make($request->all(), [
            'token' => 'required|max:10|min:10',
            'class_id' => 'required'
        ], [], [
            'token' => 'Token',
            'class_id' => 'ID Kelas'
        ]);

        if($validator->fails())
        {
            $errors = $validator->errors();
            return response()->json([
                'error' => true,
                'message' => $errors->all()
            ], 422);
        }

        $cek = ClassMember::where('user_id', $user_id)->where('class_id', $class_id)->first();
        if(!empty($cek))
        {
            return response()->json([
                'error' => true,
                'message' => 'Students are already enrolled in this class!'
            ], 422);
        }

        $cek_token = Classroom::where('id', $class_id)->where('token', $token)->first();
        if(empty($cek_token)){
            return response()->json([
                'error' => true,
                'message' => 'The class token you entered is incorrect!'
            ], 422);
        }   

        try{
            $model = new ClassMember;
            $model->class_id = $class_id;
            $model->user_id = $user_id;
            $model->save();
    
            return response()->json([
                'error' => false,
                'message' => 'Congratulations you have successfully enrolled in this class!'
            ], 200);
        }catch(\Exception $ex){
            return response()->json([
                'error' => true,
                'message' => $ex
            ], 500);
        }
    }
}
