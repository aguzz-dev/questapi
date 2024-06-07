<?php
namespace App\Controllers;

use App\Helpers\JsonResponse;
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
        JsonResponse::send(true, 'Post creado con éxito', 200, $res);
    }

    public function update()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        $res = (new Post)->update($request);
        JsonResponse::send(true, 'Post actualizado con éxito', 200, $res);
    }

    public function destroy()
    {
        $id = implode(json_decode(file_get_contents("php://input"), true));
        (new Post)->destroy($id);
        JsonResponse::send(true, 'Post eliminado correctamente');
    }
}