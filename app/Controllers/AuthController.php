<?php
namespace App\Controllers;
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Helpers\JsonResponse;
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
            JsonResponse::send(true, 'Inicio de sesión exitoso', 200 , $data);
        } catch (\Exception $e) {
            JsonResponse::exception($e);
        }
    }

    public function checkSession()
    {
        $request = json_decode(file_get_contents("php://input"));
        $userToken = (new PersonalAccessToken)->getTokenById($request->id);
        $charsToRemove = ['[','"',']'];
        $token = str_replace($charsToRemove, '', $userToken);

        if($token != $request->token){
            JsonResponse::send(false, 'Token inválido', 401);
        }
        $user = (new User)->find($request->id)[0];
        $userData = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email']
        ];
        JsonResponse::send(true, 'Sesión validada con éxito', 200, $userData);
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
        $userId = (new PersonalAccessToken)->getIdByToken($request->token);
        (new PersonalAccessToken)->destroyToken($userId);
        JsonResponse::send(true, 'Sesión eliminada con éxito');
    }
}
