<?php
namespace App\Customs\Services;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordService{
    private function validateCurrentPassword($current_password){

        if(!password_verify($current_password, auth()->user()->password)){


             throw ValidationException::withMessages([
            'current_password' => ['The current password is incorrect.']
        ]);

            // return response()->json([
            //     "status" => "Failed",
            //     "message" => "password did not match the current password"
            // ])->send();
            // exit;

        }

    }
    public function changePassword($data){
        // password current password
        $this->validateCurrentPassword($data['current_password']);
        $updatePassword = auth()->user()->update([
            "password" =>Hash::make($data['password'])
        ]);

        if($updatePassword){
                return response()->json([
                    "status" => "Success",
                    "message" => "password updated successfully"
                ]);
            }else{
                return response()->json([
                    "status" => "success",
                    "message" => "Unexpected error while updating password, please try again."
                ]);
            }
    }
}