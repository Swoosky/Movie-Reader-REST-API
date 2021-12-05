<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function register(Request $request) {
        $registrationData = $request->all();
        $validate = Validator::make($registrationData, [
            'first_name' => 'required|max:60',
            'last_name' => 'required|max:60',
            'img_url' => 'url',
            'phone_number' => 'max:13',
            'gender' => 'required',
            'email' => ['required','email:rfc,dns'],
            'username' => 'required',
            'password' => 'required'
        ]); //membuat rule

        if($validate->fails()) {
            return response(['message'=> $validate->errors()], 400); //return error
        }

        $registrationData['password'] = bcrypt($request->password);
        $registrationData['img_url'] = 'https://www.seekpng.com/png/detail/966-9665493_my-profile-icon-blank-profile-image-circle.png';
        $registrationData['phone_number'] = '0';
        $registrationData['role'] = 'Default User';
        $user = User::Create($registrationData);

        $user->sendApiEmailVerificationNotification();

        return response([
            'message' => 'Register Success, Please Check Email for Verification',
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
        
        if($user->email_verified_at == NULL){
            return response()->json(['message'=> 'Please Verify Email'], 401);  // Cek udah verif apa belom
        }

        $token = $user->createToken('Authentication Token')->accessToken; // generate token

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]); //return data user dan token dlm json
    }

    public function logout() {
        $user = Auth::user()->token();
        $user->revoke();
        
        return response([
            'message' => 'Logout Success'
        ], 200);; // modify as per your need
    }
}
