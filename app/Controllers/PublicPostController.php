<?php
namespace App\Controllers;

use App\Helpers\JsonResponse;
use App\Models\PublicPost;
use App\Middleware\VerifyToken;
use Exception;

class PublicPostController
{
    public function makePublicPost()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        VerifyToken::verifyToken($request->token);
        try{
            $res = (new PublicPost)->makePublicPost($request['id']);
            JsonResponse::send(true, 'Post publicado con éxito', 200, $res);
        }catch(Exception $e){
            JsonResponse::exception($e);
        }
    }

    public function makePrivatePost()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        VerifyToken::verifyToken($request['token']);
        try{
            $res = (new PublicPost)->makePrivatePost($request['id']);
            JsonResponse::send(true, 'Post ocultado con éxito', 200, $res);
        }catch(Exception $e){
            JsonResponse::exception($e);
        }
    }
}