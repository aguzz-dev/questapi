<?php 

namespace App\Helpers;

use Firebase\JWT\JWT;

class GenerateToken
{
    public static function auth($userData) {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600 * 24;
        $payload = array(
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => $userData
        );
        return JWT::encode($payload, SECRET_KEY, 'HS256');
    }
}