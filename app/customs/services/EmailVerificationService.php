<?php

namespace App\Customs\Services;

use App\Models\EmailVerificationTokem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailVerificationNotification;
use App\Models\User;

class EmailVerificationService
{

    public function checkIfEmailIsVerified($user){

        if($user->email_verified_at){
            return response()->json([
                "status" => "failed",
                "message" => "Email has already been vified"
            ])->send();

            exit;
        }

    }

    public function verifyEmail(string $email, string $token)
{
    $user = User::where('email', $email)->first();

    if (!$user) {
        return response()->json([
            "status" => "failed",
            "message" => "User not found!"
        ]);
    }
    if ($user->hasVerifiedEmail()) {
        return response()->json([
            "status" => "failed",
            "message" => "Email has already been verified"
        ]);
    }

    $verifiedToken = $this->verifyToken($email, $token);

    if (!$verifiedToken) {
        return response()->json([
            "status" => "failed",
            "message" => "Invalid or expired token"
        ]);
    }

    if ($user->markEmailAsVerified()) {
        $verifiedToken->delete();
        return response()->json([
            "status" => "success",
            "message" => "Email has been verified successfully"
        ]);
    } else {
        return response()->json([
            "status" => "failed",
            "message" => "Email verification failed, please try again later"
        ]);
    }
}


// Verify token
   public function verifyToken(string $email, string $token)
{
    $tokenRecord = EmailVerificationTokem::where('email', $email)->where('token', $token)->first();

    if ($tokenRecord) {
        if ($tokenRecord->expired_at >= now()) {
            return $tokenRecord;
        } else {
             $tokenRecord->delete();
            return null;
        }
    } 

    return null;
}
    // send velificalin link

    public function sendVerificationLink(object $user): void
    {
        $verificationLink = $this->generateVerificationLink($user->email);
        Notification::send($user, new EmailVerificationNotification($verificationLink));
    }

    // resend the link once the first expired

    public function resendLink($email){
        $user = User::where('email', $email)->first();
        if($user){
            $this->sendVerificationLink($user);
            return response()->json([
                "status" => "Success",
                "message" => "verification Link sent successfully"
            ]);
        }else{
            return response()->json([
                "status" => "failed",
                "message" => "User not found !"
            ]);
        }
    }


    public function generateVerificationLink(string $email): string
    {
        $existingToken = EmailVerificationTokem::where('email', $email)->first();

        if ($existingToken) {
            $existingToken->delete();
        }

        $token = Str::uuid();
        $url = config('app.url'). "?token=".$token . "&email=" . $email;
        $saved = EmailVerificationTokem::create([
            'email' => $email,
            'token' => $token,
            'expired_at' => now()->addMinutes(60),
        ]);

        return $url;
    }
}
