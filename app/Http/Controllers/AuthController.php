<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;

class AuthController extends Controller
{
    protected function responseWithToken($token)
    {
        return response()->json([
            'error' => false,
            'message' => 'Congratulations! you have successfully logged in',
            'data' => [
                'user' => Auth::user(),
                'token' => [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => Auth::factory()->getTTL() * 60
                ],
            ]
        ]);
    }

    public function login(Request $request)
    {
        $login = $request->login;
        
        $attrs = [
            'login' => 'E-Mail or Username',
            'password' => 'Password'
        ];
        $validator = Validator::make($request->all(), [
            'login' => 'required',
            'password' => 'required|min:8'
        ], [], $attrs);

        if($validator->fails())
        {
            $errors = $validator->errors();
            return ResponseHelper::responseValidation($errors);
        }

        $login_type = filter_var($login, FILTER_VALIDATE_EMAIL ) ? 'email' : 'username';

        $request->merge([
            $login_type => $login
        ]);

        $credentials = request([$login_type, 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return ResponseHelper::responseError('Unauthorized', 401);
        }

        return $this->responseWithToken($token);
    }

    public function register(Request $request)
    {
        $full_name = $request->full_name;
        $username = $request->username;
        $email = $request->email;
        $password = $request->password;
        $re_password = $request->re_password;
        $type = $request->type; //[student, teacher]

        $attrs = [
            'full_name' => 'Nama Lengkap',
            'username' => 'Username',
            'email' => 'E-Mail',
            'password' => 'Password',
            're_password' => 'Konfirmasi Password'
        ];

        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'username' => [
                'required',
                'max:15',
                Rule::unique('users')
            ],
            'email' => [
                'required',
                Rule::unique('users'),
                'email'
            ],
            'password' => 'min:8|required|same:re_password',
            're_password' => 'min:8|required'
        ], [], $attrs);

        if($validator->fails())
        {
            $errors = $validator->errors();
            return ResponseHelper::responseValidation($errors);
        }

        try{
            $model = new User;
            $model->name = $full_name;
            $model->username = $username;
            $model->email = $email;
            $model->password = Hash::make($password);
            $model->type = $type;
            $model->save();

            return ResponseHelper::responseSuccess('Congratulations! you have successfully registered');
        }catch(\Exception $ex){
            return ResponseHelper::responseError($ex, 500);
        }
    }

    public function logout()
    {
        Auth::logout();
        return ResponseHelper::responseSuccess('Congratulations! you successfully logged out');
    }
}
