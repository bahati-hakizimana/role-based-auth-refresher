<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Posts\PostController;


Route::middleware(['auth:api'])->group(function (){
    Route::post('post/create', [PostController::class, 'store']);
    Route::post('/post/{post}', [PostController::class,'update']);
});


