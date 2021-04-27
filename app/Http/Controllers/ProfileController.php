<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function get_avatar($id)
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

    public function update_account(Request $request, $id)
    {
        $email = $request->email;
        $password = $request->password;
        $re_password = $request->re_password;

        if(!empty($email))
        {
            return $this->updateEmail($email, $id);
        }

        if(!empty($password))
        {
            return $this->updatePassword($password, $re_password, $id);
        }
    }

    protected function updateEmail($email, $id)
    {
        $validator = Validator::make(
    [
            'email' => $email
        ], 
        [
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ]
        ], [], 
        [
            'email' => 'E-Mail'
        ]
        );

        if($validator->fails())
        {
            $errors = $validator->errors();
            return ResponseHelper::responseValidation($errors);
        }
        else
        {
            try{
                $model = User::findOrFail($id);
                $model->email = $email;
                $model->save();

                return ResponseHelper::responseSuccess('Congratulations! your email has been successfully changed.');
            }catch(\Exception $ex){
                return ResponseHelper::responseError($ex, 500);
            }

        }
    }

    protected function updatePassword($password, $re_password, $id)
    {
        $validator = Validator::make(
            [
                'password' => $password,
                're_password' => $re_password
            ], 
            [
                'password' => 'min:8|required|same:re_password',
                're_password' => 'min:8|required'
            ], [], 
            [
                'password' => 'Password',
                're_password' => 'Konfirmasi Password'
            ]
        );
    
        if($validator->fails())
        {
            $errors = $validator->errors();
            return ResponseHelper::responseValidation($errors);
        }
        else
        {
            try{
                $model = User::findOrFail($id);
                $model->password = Hash::make($password);
                $model->save();

                return ResponseHelper::responseSuccess('Congratulations! your password has been successfully changed.');
            }catch(\Exception $ex){
                return ResponseHelper::responseError($ex, 500);
            }

        }
    }
}
