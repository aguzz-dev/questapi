<?php
namespace App\Controllers;

use Exception;
use App\Models\User;
use App\Helpers\JsonRequest;
use App\Helpers\JsonResponse;
use App\Middleware\VerifyToken;
use App\Request\UpdateUserRequest;
use App\Request\RegisterUserRequest;

class UserController
{
    public function store()
    {
        $request = JsonRequest::get();
        RegisterUserRequest::validate($request);
        $res = (new User)->store($request);
        jsonResponse::send(true, 'Usuario registrado correctamente', 200, $res);
    }

    public function update()
    {
        VerifyToken::jwt();
        $request = JsonRequest::get();
        UpdateUserRequest::validate($request);
        try {
            $res = (new User)->update($request);
            JsonResponse::send(true, 'Usuario actualizado con éxito', 200, $res);
        } 
        catch (Exception $e) {
            JsonResponse::exception($e);
        }
    }

    public function destroy()
    {
        VerifyToken::jwt();
        $request = JsonRequest::get();
        if(!isset($request->id)){
            http_response_code(422);
            echo json_encode(['El campo id es obligatorio']);
            exit;
        }
        try{
            (new User)->destroy($request->id);
            JsonResponse::send(true, 'Usuario eliminado correctamente del sistema');
        }
        catch(Exception $e){
            JsonResponse::exception($e);
        }
    }

    public function changePassword()
    {
        VerifyToken::jwt();
        $request = JsonRequest::get();
        try{
            (new User)->changePassword($request);
            JsonResponse::send(true, 'Password actualizada con éxito');
        }catch(Exception $e){
            JsonResponse::exception($e);
        }
    }
}