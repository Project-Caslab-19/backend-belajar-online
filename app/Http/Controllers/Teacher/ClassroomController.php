<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Helpers\ResponseHelper;
use App\Models\ClassMember;
use App\Models\Learning;
use App\Models\Quiz;
use App\Models\Topic;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClassroomController extends Controller
{
    public function get_classroom()
    {
        $user_id = Auth::user()->id;
        try{
            $data = Classroom::with('category')->withCount('members')->withCount('topics')->where('user_id', $user_id)->get();
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

    private function getToken()
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = substr(str_shuffle($permitted_chars), 0, 10);

        $cek = Classroom::where('token', $token)->first();
        if(!empty($cek))
            $this->getToken();

        return $token;
    }

    public function create_classroom(Request $request)
    {
        $user_id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('classroom'),
            ],
            'category_id' => 'required',
            'description' => 'required',
        ], [], [
            'category_id' => 'Category',
        ]);

        if($validator->fails())
        {
            $errors = $validator->errors();
            return ResponseHelper::responseValidation($errors);
        }

        try{
            Classroom::create([
                'name' => $request->name,
                'photo' => 'photo.png',
                'user_id' => $user_id,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'level' => 'Beginner',
                'token' => $this->getToken()
            ]);

            return ResponseHelper::responseSuccess('Success create a classroom');
        }catch(Exception $ex)
        {
            return ResponseHelper::responseError($ex, 500);
        }
    }

    public function edit_classroom(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('classroom')->ignore($id),
            ],
            'category_id' => 'required',
            'description' => 'required',
        ], [], [
            'category_id' => 'Category',
        ]);

        if($validator->fails())
        {
            $errors = $validator->errors();
            return ResponseHelper::responseValidation($errors);
        }

        try{
            $classroom = Classroom::find($id);
            $classroom->name = $request->name;
            $classroom->category_id = $request->category_id;
            $classroom->description = $request->description;
            $classroom->save();

            return ResponseHelper::responseSuccess('Success edit a classroom');
        }catch(Exception $ex)
        {
            return ResponseHelper::responseError($ex->getMessage(), 500);
        }
    }

    public function delete_classroom($id)
    {
        $members = ClassMember::where('class_id', $id)->get();
        $topics = Topic::where('class_id', $id)->get();
        $learnings = Learning::with('topic')->whereHas('topic', function($q) use($id){
            $q->where('class_id', $id);
        })->get();
        $quizzes = Quiz::with('topic')->whereHas('topic', function($q) use($id){
            $q->where('class_id', $id);
        })->get();

        if(!empty($cek_member) && !empty($topics) && !empty($learnings) && !empty($quizzes))
        {
            return ResponseHelper::responseValidation('Classroom cannot deleted because has members, topics, learnings, and quizzes');
        }

        try{
            Classroom::destroy($id);
            return ResponseHelper::responseSuccess('Success delete a classroom');
        }catch(Exception $ex)
        {
            return ResponseHelper::responseError($ex->getMessage(), 500);
        }
    }
}
