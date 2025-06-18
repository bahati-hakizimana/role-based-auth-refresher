<?php

namespace App\Http\Controllers\Api\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Requests\PostRequest;
use App\Customs\Services\Post\PostService;

class PostController extends Controller
{
    //

    public function __construct(private PostService $post){}

    public function store(PostRequest $request){

        $validatedData = $request->validated();
        try{
            $post = $this->post->create($validatedData);
            return response()->json([
                "status" => "success",
                "mess" => "Post created successfully",
                "data" => $post
            ]);
        }catch(\Throwable $th){

            return response()->json([
                "status" => "failed",
                "message" => "failed to create post, please try again"
            ]);

        }


    }

    public function update(PostRequest $request, Post $post){


        try{
            $validatedData = $request->validated();
            $updatedpost = $this->post->updatePost($post, $validatedData);
            return response()->json([
                "status" => "success",
                "message" => "post updated successfully",
                "data" => $updatedpost
            ]);

        }catch(\Throwable $th){
            return response()->json([
                "status" => "failed",
                "message" => "Failed to update post, please try again",
                "error" => $th->getMessage()
            ]);
        }

    }
}
