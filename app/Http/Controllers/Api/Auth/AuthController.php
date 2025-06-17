<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Requests\ResendEmailVerificationRequest;
use App\Customs\Services\EmailVerificationService;


class AuthController extends Controller
{

    public function __construct(private EmailVerificationService $service){}

    public function register(RegistrationRequest $request){


       $user = User::create($request->validated());
       $token = JWTAuth::attempt([

        "email" => $request->email,
        "password" => $request->password
    ]);

    if ($user && $token) {
        $this->service->sendVerificationLink($user);
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
            "accessToken" => $token,
            "user" => $user,
            "type" => "bearer"
        ]);
    }

    public function login(LoginRequest $request){
        $token = JWTAuth::attempt($request->validated());
        if(!empty($token)){
            return $this->responseWithToken($token, auth()->user());
        }else{
            return response()->json([
                "status" => "failed",
                "message" => "Invalid credentials"
            ]);
        }
    }

    public function verifyUserEmail(VerifyEmailRequest $request){

        return $this->service->verifyEmail($request->email, $request->token);

    }

    public function resendEmailVerificationLink(ResendEmailVerificationRequest $request){
        return $this->service->resendLink($request->email);
    }

    public function profile(){
    }

    public function logout(){}
     
    public function refreshToken(){}
}
