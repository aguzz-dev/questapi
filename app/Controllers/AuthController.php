<?php
namespace App\Controllers;
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\User;
use \Firebase\JWT\JWT;

class AuthController {
    private static $secretKey = "1111";

    public function login()
    {
        $request = $_POST;
        session_start();
        $res = (new User)->login($request);
        return $res;
    }

    public static function generateToken($userData) {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600 * 24;
        $payload = array(
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => $userData
        );

        return JWT::encode($payload, self::$secretKey, 'HS256');
    }
}
