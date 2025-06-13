<?php

namespace App\Customs\Services;
use App\Models\EmailVerificationTokem;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailVerificationNotification;

class EmailVerificationService{

    public function sendVerificationLink(object $user):void{

        Notification::send($user, new EmailVerificationNotification($this->generateVerificationLink($user->email)));

    }
    public function generateVerificationLink(string $emai): string
    {

        $checkIfTokEnexist = EmailVerificationTokem::where('email', $emai)->first();

        if($checkIfTokEnexist)$checkIfTokEnexist->delete();
        $token = Str::uuid();
        $url = config('app.url'). "?token=".$token . "&email=" . $email;
        $saveToke = EmailVerificationTokem::create([
            "email" => $email,
            "token" => $token,
            "expired_date" => now()->addMinutes(60)
        ]);

        if($saveToke){
            return $url;
        }

    }
}