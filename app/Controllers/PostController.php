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
        $request = $_POST;
        $res = (new Post)->store($request);
        return $res;
    }

    public function update()
    {
        parse_str(file_get_contents("php://input"),$request);
        $res = (new Post)->update($request);
        return $res;
    }

    public function destroy()
    {
        parse_str(file_get_contents("php://input"),$product);
        $res = (new Post)->destroy($product['id']);
        return $res;
    }
}