<?php
namespace App\Controllers;

use App\Models\Post;
use App\Helpers\JsonRequest;
use App\Helpers\JsonResponse;
use App\Middleware\VerifyToken;

class PostController
{
    public function index():array
    {
        $res = (new Post)->getAllPosts();
        return $res;
    }

    public function store()
    {
        $request = JsonRequest::get();
        VerifyToken::verifyToken($request->token);
        $res = (new Post)->store($request);
        JsonResponse::send(true, 'Post creado con éxito', 200, $res);
    }

    public function update()
    {
        $request = JsonRequest::get();
        VerifyToken::verifyToken($request->token);
        $res = (new Post)->update($request);
        JsonResponse::send(true, 'Post actualizado con éxito', 200, $res);
    }

    public function destroy()
    {
        $id = JsonRequest::get()->id;
        (new Post)->destroy($id);
        JsonResponse::send(true, 'Post eliminado correctamente');
    }
}