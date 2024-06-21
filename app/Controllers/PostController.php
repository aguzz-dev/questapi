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
        $userId = JsonRequest::get()->id;
        $res = (new Post)->getAllPosts($userId);
        return $res;
    }

    public function store()
    {
        $request = JsonRequest::get();
        VerifyToken::jwt($request->token);
        $res = (new Post)->store($request);
        JsonResponse::send(true, 'Post creado con éxito', 200, $res);
    }

    public function update()
    {
        $request = JsonRequest::get();
        VerifyToken::jwt($request->token);
        $res = (new Post)->update($request);
        JsonResponse::send(true, 'Post actualizado con éxito', 200, $res);
    }

    public function destroy()
    {
        $request = JsonRequest::get();
        VerifyToken::jwt($request->token);
        (new Post)->destroy($request->id);
        JsonResponse::send(true, 'Post eliminado correctamente');
    }
}