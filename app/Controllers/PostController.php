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
        VerifyToken::jwt();
        $userId = JsonRequest::get()->id;
        $res = (new Post)->getAllPosts($userId);
        return $res;
    }

    public function getPostByIdWithQuestions()
    {
        VerifyToken::jwt();
        $res = (new Post)->findById(JsonRequest::get()->id);
        return !empty($res) ? $res : JsonResponse::send(false, 'No se ha podido encontrar ningún post público', 422);
    }

    public function store()
    {
        VerifyToken::jwt();
        $request = JsonRequest::get();
        $res = (new Post)->store($request);
        JsonResponse::send(true, 'Post creado con éxito', 200, $res);
    }

    public function update()
    {
        VerifyToken::jwt();
        $request = JsonRequest::get();
        $res = (new Post)->update($request);
        JsonResponse::send(true, 'Post actualizado con éxito', 200, $res);
    }

    public function destroy()
    {
        VerifyToken::jwt();
        $request = JsonRequest::get();
        (new Post)->destroy($request->id);
        JsonResponse::send(true, 'Post eliminado correctamente');
    }
}