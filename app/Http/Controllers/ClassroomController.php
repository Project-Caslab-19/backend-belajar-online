<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\ClassMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;
use Illuminate\Auth\Access\Response;

class ClassroomController extends Controller
{
    public function get_all_classroom(){
        try{
            $data = Classroom::with('category')->get();
            return ResponseHelper::responseSuccessWithData($data);
        }catch(\Exception $ex){
            return ResponseHelper::responseError($ex, 500);
        }
    }   

    public function get_detail_classroom($id)
    {
        try{
            $classroom = Classroom::with('topics', 'category', 'members', 'members.user','user')->findOrFail($id);
            return ResponseHelper::responseSuccessWithData($classroom);
        }catch(\Exception $ex){
            return ResponseHelper::responseError('Data not found!', 404);
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
            return ResponseHelper::responseValidation($errors);
        }

        $cek = ClassMember::where('user_id', $user_id)->where('class_id', $class_id)->first();
        if(!empty($cek))
        {
            return ResponseHelper::responseValidation('Students are already enrolled in this class!');
        }

        $cek_token = Classroom::where('id', $class_id)->where('token', $token)->first();
        if(empty($cek_token)){
            return ResponseHelper::responseValidation('The class token you entered is incorrect!');
        }   

        try{
            $model = new ClassMember;
            $model->class_id = $class_id;
            $model->user_id = $user_id;
            $model->save();
                
            return ResponseHelper::responseSuccess('Congratulations you have successfully enrolled in this class!');
        }catch(\Exception $ex){
            return ResponseHelper::responseError($ex, 500);
        }
    }
}
