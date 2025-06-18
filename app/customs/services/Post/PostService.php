<?php
namespace App\Customs\Services\Post;
use App\Models\Post;

class PostService{
    public function create(array $data){
        $post = auth()->user()->posts()->create($data);
        return $data;
    }

    public function updatePost(Post $post, array $data)
{
    if ($post->user_id !== auth()->id()) {
        throw new \Illuminate\Auth\Access\AuthorizationException('You do not have permission to update this post.');
    }

    $post->update($data);

    return $post;
}

}