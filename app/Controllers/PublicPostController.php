<?php
namespace App\Controllers;

use App\Models\PublicPost;
use App\Middleware\VerifyToken;

class PublicPostController
{
    public function makePublicPost()
    {
        VerifyToken::verifyToken($_POST['token']);
        $res = (new PublicPost)->makePublicPost($_POST['id']);
        return $res;
    }

    public function makePrivatePost()
    {
        VerifyToken::verifyToken($_POST['token']);
        $res = (new PublicPost)->makePrivatePost($_POST['id']);
        return $res;
    }
}