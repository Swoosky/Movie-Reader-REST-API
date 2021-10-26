<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        $registrationData = $request->all();
        $validate = Validator::make($registrationData, [
            'name' => 'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required'
        ]); //membuat rule

        if($validate->fails()) {
            return response(['message'=> $validate->errors()], 400); //return error
        }

        $registrationData['password'] = bcrypt($request->password);
        $user = User::Create($registrationData);
        return response([
            'message' => 'Register Success',
            'user' => $user,
        ], 200);
    }

    public function login(Request $request) {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]); //membuat rule

        if($validate->fails()) {
            return response(['message'=> $validate->errors()], 400); //return validasi input
        }

        if(!Auth::attempt($loginData)) {
            return response(['message'=> 'Invalid Credentials'], 401); //return gagal login
        }

        $user = Auth::user();
        $token = $user->createToken('Authentication Token')->accessToken; // generate token

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]); //return data user dan token dlm json
    }
}
