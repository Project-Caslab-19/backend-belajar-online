<?php

namespace App\Http\Controllers\Teacher;

use App\Models\User;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

    public function add_class(Request $request, $id)
    {
        // req nama_classroom, desc
        $user_id = Auth::user()->id;
        $class_name = $request->input('name');
        $class_category = $request->input('category_id');
        $class_level = $request->input('level');
        $class_description = $request->input('description');
        $token = $request->token;
        // req user_id
        try{
            $user = User::where('id', $id)->firstOrFail();
        }catch(\Exception $ex)
        {
            return ResponseHelper::responseError($ex, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('classroom')->ignore($id)
            ],
            'category_id' => 'required',
            'level' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048'
        ], [], [
            'name' => 'Nama Kelas',
            'category_id' => 'Kategori',
            'description' => 'Deskripsi Kelas',
            'level' => 'Level Kelas',
            'image' => 'Photo'
        ]);

        if($validator->fails())
        {
            $errors = $validator->errors();
            return ResponseHelper::responseValidation($errors);
        }

        if($request->hasFile('image'))
        {
            try{
                $imageName = time().'.'.$request->image->extension(); 
                $request->image->move(storage_path('uploads/classroom/photos'), $imageName);
            }catch(\Exception $ex){
                return ResponseHelper::responseError('Error when uploading image!', 500);
            }

            try{
                $update_img = User::findOrFail($id);
                $update_img->photo = $imageName;
                $update_img->save();
            }catch(\Exception $ex){
                return ResponseHelper::responseError($ex, 500);
            }
        }
        
        try{
            $model = User::findOrFail($id);
            $model->name = $class_name;
            $model->category = $class_category;
            $model->description = $class_description;
            $model->level = $class_level;
            $model->save();        
            
            return ResponseHelper::responseSuccess('Congratulations! you have successfully added new classroom.');
        }catch(\Exception $ex){
            return ResponseHelper::responseError($ex, 500);
        }
    }

    public function edit_class($id)
    {
        
    }

    public function delete_class($id)
    {

    }

}
