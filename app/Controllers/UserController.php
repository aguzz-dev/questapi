<?php
namespace App\Controllers;

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
        return $res;
    }

    public function update()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        UpdateUserRequest::validate($request);
        try {
            $res = (new User)->update($request);
            return $res;
        } 
        catch (Exception $e) {
            http_response_code($e->getCode());
            return [
                'status'    => 'error',
                'message'   => $e->getMessage()
            ];
        }
    }

    public function destroy()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        if(!isset($request['id'])){
            http_response_code(422);
            echo json_encode(['El campo id es obligatorio']);
            exit;
        }
        try{
            $res = (new User)->destroy($request['id']);
            return $res;
        }
        catch(Exception $e){
            http_response_code($e->getCode());
            return [
                'status'    => 'error',
                'message'   => $e->getMessage()
            ];
        }
    }
}