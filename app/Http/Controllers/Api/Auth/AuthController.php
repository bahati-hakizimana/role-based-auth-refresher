<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\RegistrationRequest;

class AuthController extends Controller
{
    //

    public function register(RegistrationRequest $request){


       $user = User::create($request->validated());
    $credentials = $request->only('email', 'password');
    $token = JWTAuth::attempt($credentials);

    if ($user && $token) {
        return $this->responseWithToken($token, $user);
    } else {
        return response()->json([
            "status" => "Failed",
            "message" => "An error occurred, please try again"
        ], 500);
    }

    }

    public function responseWithToken($token, $user){
        return response()->json([
            "status" => "Success",
            "message" => "User created successfully",
            "accessToken" => $token,
            "user" => $user,
            "type" => "bearer"
        ]);
    }

     public function login(Request $request){
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $token = JWTAuth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if(!empty($token)){
            return response()->json([
                "status" => true,
                "messagege" => "User loged in successfully",
                "token" => $token,
                "user" => $user
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Invalid user credentials"
        ]);
     }

    public function profile(){}

    public function logout(){}
     
    public function refreshToken(){}
}
