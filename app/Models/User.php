<?php
namespace App\Models;
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
        return $this->find($idUser);
    }

    public function login($request)
    {
        $username = $request['username'];
        $password = $request['password'];

        $sql = "SELECT * FROM {$this->table} WHERE username = '{$username}' LIMIT 1";
        $result = $this->query($sql);

        if ($result == null) {
            return [
                'status'    => 'error',
                'message'   => 'Usuario no encontrado'
            ];
        }

        $user = $result->fetch_assoc();
        if (!password_verify($password, $user['password'])) {
            return [
                'status' => 'error',
                'message' => 'Contraseña incorrecta'
            ];
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