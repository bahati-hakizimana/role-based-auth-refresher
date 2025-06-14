<?php

namespace App\Customs\Services;

use App\Models\EmailVerificationTokem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailVerificationNotification;

class EmailVerificationService
{
    public function verifyToken(string $email, string $token):void{



    }
    public function sendVerificationLink(object $user): void
    {
        $verificationLink = $this->generateVerificationLink($user->email);
        Notification::send($user, new EmailVerificationNotification($verificationLink));
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
