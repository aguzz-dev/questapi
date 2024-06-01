<?php
namespace App\Controllers;

use App\Models\PublicPost;
use App\Middleware\VerifyToken;

class PublicPostController
{
    public function makePublicPost()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        VerifyToken::verifyToken($request['token']);
        $res = (new PublicPost)->makePublicPost($request['id']);
        return $res;
    }

    public function makePrivatePost()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        VerifyToken::verifyToken($request['token']);
        $res = (new PublicPost)->makePrivatePost($request['id']);
        return $res;
    }
}