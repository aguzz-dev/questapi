<?php
namespace App\Controllers;
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\User;
use \Firebase\JWT\JWT;

class AuthController {
    private static $secretKey = "1111";

    public function login()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        session_start();
        try {
            $data = (new User)->login($request);
            return [
                'status'    => 'success',
                'message'   => 'Inicio de sesión exitoso',
                'data'      => $data
            ];
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            return [
                'status'    => 'error',
                'message'   => $e->getMessage()
            ];
        }
    }

    public function checkSession()
    {
        session_start();
        $request = file_get_contents("php://input");
        $charsToRemove = ['token','{','}',':', '=','"'];
        $token = trim(str_replace($charsToRemove,'',$request));
        if($_SESSION['token'] != $token){
            http_response_code(401);
            echo json_encode([
                'status' => 'error',
                'message' => 'Token inválido',
                'status_code' => 401
            ]);
            exit;
        } 
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Sesión validada con éxito',
            'status_code' => 200,
            'user' => [
                'id' => $_SESSION['userId'],
                'username' => $_SESSION['username'],
                'email' => $_SESSION['email']
            ]
        ]);
        exit;
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
