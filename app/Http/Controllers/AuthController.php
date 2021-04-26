<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $login = $request->login;
        $password = $request->password;
        
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
            return response()->json([
                'error' => true,
                'form_error' => $errors->all(),
                'message' => 'You must fill all the fields'
            ], 422);
        }

        $login_type = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL ) ? 'email' : 'username';

        $request->merge([
            $login_type => $request->input('login')
        ]);

        $credentials = request([$login_type, 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
