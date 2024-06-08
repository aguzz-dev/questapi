<?php
namespace App\Controllers;
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Helpers\JsonRequest;
use App\Helpers\JsonResponse;
use App\Models\PersonalAccessToken;
use App\Models\User;
use App\Request\LoginRequest;

class AuthController 
{
    public function login()
    {
        $request = JsonRequest::get();
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
        $request = JsonRequest::get();
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
    
    public function logout()
    {
        $request = JsonRequest::get();
        $userId = (new PersonalAccessToken)->getIdByToken($request->token);
        (new PersonalAccessToken)->destroyToken($userId);
        JsonResponse::send(true, 'Sesión eliminada con éxito');
    }
}
