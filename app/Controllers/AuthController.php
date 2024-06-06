<?php
namespace App\Controllers;
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\PersonalAccessToken;
use App\Models\User;
use App\Request\LoginRequest;
use \Firebase\JWT\JWT;

class AuthController {
    private static $secretKey = "1111";

    public function login()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        LoginRequest::validate($request);
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
        $request = json_decode(file_get_contents("php://input"));
        $userToken = (new PersonalAccessToken)->getTokenById($request->id);
        $charsToRemove = ['[','"',']'];
        $token = str_replace($charsToRemove, '', $userToken);

        if($token != $request->token){
            http_response_code(401);
            echo json_encode([
                'status' => 'error',
                'message' => 'Token inválido',
                'status_code' => 401
            ]);
            exit;
        }
        $user = (new User)->find($request->id)[0];
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Sesión validada con éxito',
            'status_code' => 200,
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email']
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

    public function destroyToken()
    {
        $request = json_decode(file_get_contents("php://input"));
        $res = (new PersonalAccessToken)->destroyToken($request->id);
        return $res;

    }
}
