<?php
namespace App\Controllers;

use App\Models\Post;

class PostController
{
    public function index():array
    {
        $res = (new Post)->getAllPosts();
        return $res;
    }

    public function store()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        $res = (new Post)->store($request);
        return $res;
    }

    public function update()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        $res = (new Post)->update($request);
        return $res;
    }

    public function destroy()
    {
        $product = json_decode(file_get_contents("php://input"), true);
        $res = (new Post)->destroy($product['id']);
        return $res;
    }
}