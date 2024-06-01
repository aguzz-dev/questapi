<?php
namespace App\Models;
use App\Request\RegisterUserRequest;
use Database;
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
        RegisterUserRequest::validate($request);
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
        return [
            'status' => 'success',
            'message' => 'Usuario registrado correctamente.',
            'data' => $userData
        ];
            
    }

    public function login($request)
    {
        $email = $request['email'];
        $password = $request['password'];

        $user = $this->query("SELECT * FROM {$this->table} WHERE email = '{$email}' LIMIT 1")->fetch_assoc();
        if (is_null($user)) {
            throw new \Exception('Usuario no encontrado', 404);
        }
        if (!password_verify($password, $user['password'])) {
            return 422;
        }

        $token = AuthController::generateToken([
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email']
        ]);
        $_SESSION['userId'] = $user['id'];
        $_SESSION['username'] = $user['username']; 
        $_SESSION['email'] = $user['email'];
        $_SESSION['token'] = $token;
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
            http_response_code(422);
            return "Usuario no encontrado.";
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
            http_response_code(422);
            return "Post no encontrado.";
        }
        $sql = "DELETE FROM {$this->table} WHERE id = {$id}";
        $this->query($sql);
        return 'Usuario eliminado correctamente del sistema';
    }
}