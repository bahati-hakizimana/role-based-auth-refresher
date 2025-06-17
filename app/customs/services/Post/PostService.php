<?php
namespace App\Customs\Services;

class PostService{
    public function create($data){
        $post = auth()->user()->posts()->create($data);
        return $data;
    }
}