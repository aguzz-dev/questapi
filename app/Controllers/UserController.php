<?php
namespace App\Controllers;

use App\Helpers\JsonResponse;
use App\Models\User;
use App\Request\RegisterUserRequest;
use App\Request\UpdateUserRequest;
use Exception;

class UserController
{
    public function store()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        RegisterUserRequest::validate($request);
        $res = (new User)->store($request);
        jsonResponse::send(true, 'Usuario registrado correctamente', 200, $res);
    }

    public function update()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        UpdateUserRequest::validate($request);
        try {
            $res = (new User)->update($request);
            JsonResponse::send(true, 'Usuario actualizado con éxito', 200, $res);
        } 
        catch (Exception $e) {
            JsonResponse::send(false, $e->getMessage(), $e->getCode());
        }
    }

    public function destroy()
    {
        $request = json_decode(file_get_contents("php://input"), true);
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
            JsonResponse::send(false, $e->getMessage(), $e->getCode());
        }
    }

/*     public function changePassword()
    {
        $request = json_decode(file_get_contents('php://input', true));
        $res = (new User)->changePassword($request);
        return $res;
    } */
}