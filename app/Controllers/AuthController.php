<?php
namespace App\Controllers;
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\User;
use App\Helpers\getHeader;
use App\Helpers\JsonRequest;
use App\Helpers\JsonResponse;
use App\Request\LoginRequest;
use App\Models\PersonalAccessToken;

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

        if($token != getHeader::token()){
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
        $userId = (new PersonalAccessToken)->getIdByToken(getHeader::token());
        (new PersonalAccessToken)->destroyToken($userId);
        JsonResponse::send(true, 'Sesión eliminada con éxito');
    }
}
