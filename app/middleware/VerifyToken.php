<?php

namespace App\Middleware;

use App\Controllers\AuthController;

class VerifyToken
{
    public static function verifyToken($token)
    {
        session_start(); 
        if($_SESSION['token'] != $token){
            http_response_code(401);
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid token'
            ]);
            exit;
        }
        return http_response_code(200);
    }
}
