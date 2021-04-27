<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;

class ProfileController extends Controller
{
    public function get_user_profile()
    {
        try{
            $data = Auth::user();
            return ResponseHelper::responseSuccessWithData($data);
        }catch(\Exception $ex){
            return ResponseHelper::responseError('Error Data!', 500);
        }
    }

    public function update_profile(Request $request, $id)
    {
        $full_name = $request->input('full_name');
        $username = $request->input('username');
        $email = $request->input('email');

        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'username' => [
                'required',
                Rule::unique('users')->ignore($id)
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id)
            ],
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ], [], [
            'full_name' => 'Nama Lengkap',
            'username' => 'Username',
            'email' => 'E-Mail',
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
                $request->image->move(storage_path('uploads/students/photos'), $imageName);
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
            $model->name = $full_name;
            $model->username = $username;
            $model->email = $email;
            $model->save();        
            
            return ResponseHelper::responseSuccess('Congratulations! you have successfully changed your profile.');
        }catch(\Exception $ex){
            return ResponseHelper::responseError($ex, 500);
        }
    }

    public function getAvatar($id)
    {
        try{
            $user = User::where('id', $id)->firstOrFail();
        }catch(\Exception $ex)
        {
            return ResponseHelper::responseError($ex, 404);
        }
        
        $path = storage_path('uploads/students/photos/') . $user->photo;
    
        if(!File::exists($path)) 
            $path = storage_path('uploads/students/photos/') . 'default.png';

        $file = File::get($path);
        $type = File::mimeType($path);

        return (new Response($file, 200))->header('Content-Type', $type);
    }

}
