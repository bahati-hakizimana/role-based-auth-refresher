<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Posts\PostController;


Route::middleware(middleware: ['auth'])->group(function(){
    Route::post('post/strore', [PostController::class, 'store']);

});

