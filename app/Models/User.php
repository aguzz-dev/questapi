<?php
namespace App\Models;

use Database;
use Exception;
use App\Controllers\AuthController;

class User extends Database
{
    protected $table = 'users';

    public function find($id):array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = {$id}";
        return $this->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function store($request)
    {
        $values = array_values($request);
        $sql = "INSERT INTO {$this->table} 
                (`full_name`, 
                `username`, 
                `email`, 
                `password`) 
                VALUES (
                    '{$values[0]}',
                    '{$values[1]}',
                    '{$values[2]}',
                    '" . password_hash($values[3], PASSWORD_DEFAULT) . "'
                )";

        $this->query($sql);
        $idUser = $this->dbConnection->insert_id;
        $userData = $this->query("SELECT id, full_name, username, email FROM {$this->table} WHERE id = {$idUser}")->fetch_all(MYSQLI_ASSOC);
        return $userData;
    }

    public function login($request)
    {
        $email = $request['email'];
        $password = $request['password'];

        $user = $this->query("SELECT * FROM {$this->table} WHERE email = '{$email}' LIMIT 1")->fetch_assoc();
        if (is_null($user)) {
            throw new Exception('Usuario no encontrado', 404);
        }
        if (!password_verify($password, $user['password'])) {
            throw new Exception('Credenciales incorrectas', 422);
        }
        $token = AuthController::generateToken([
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email']
        ]);
        $this->query("INSERT INTO `personal_access_tokens` (`token`, `user_id`) VALUES ('{$token}', '{$user['id']}')");
        $userData = [
            'id' => $user['id'],
            'full_name' => $user['full_name'],
            'username' => $user['username'],
            'email' => $user['email']
        ];
        return [
            'status' => 'success',
            'token' => $token,
            'user' => $userData
        ];
    }


    public function update($request)
    {
        $User = $this->find($request['id']);
        if(!$User){
            throw new Exception('Usuario no encontrado', 404);
        }
        $fields = [];
        foreach ($request as $key => $value) {
            $fields[] = "{$key} = '{$value}'";
        }
        $fields = implode(', ', $fields);
        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = {$request['id']}";
        $this->query($sql);
        return $this->find($request['id']);
    }

    public function destroy($id)
    {
        $user = $this->find($id);
        if(!$user){
            throw new Exception('Usuario no encontrado', 404);
        }
        $sql = "DELETE FROM {$this->table} WHERE id = {$id}";
        $this->query($sql);
    }
/* 
    public function changePassword($request)
    {
        VerifyToken::verifyToken($request->token);
        $password = password_hash($request->password, PASSWORD_DEFAULT);
        $userId = (new PersonalAccessToken)->getIdByToken($request->token);
        $this->query("UPDATE `users` SET `password` = {$password} WHERE id = {$userId}");
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Password actualizada con exito'
        ]);
        exit;
    } */
}