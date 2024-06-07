<?php

namespace  App\Models;

use Database;

class PersonalAccessToken extends Database
{
    protected $table = 'personal_access_tokens';

    public function find($token):array
    {
        $sql = "SELECT * FROM {$this->table} WHERE token = {$token}";
        return $this->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getTokenById($id)
    {
        $token = $this->query("SELECT `token` FROM {$this->table} WHERE user_id = {$id} ORDER BY id DESC LIMIT 1;")->fetch_row();
        return  json_encode($token);
    }

    public function validateToken($token)
    {
        $token = $this->query("SELECT * FROM {$this->table} WHERE `token` = '{$token}'")->fetch_all(MYSQLI_ASSOC);
        if(empty($token)){
            http_response_code(401);
            echo json_encode([
                'status' => 'error',
                'message' => 'Token invalido'
            ]);
            exit;
        }
        http_response_code(200);
    }

    public function getIdByToken($token)
    {
        $id = $this->query("SELECT id FROM {$this->table} WHERE `token` = '{$token}'")->fetch_all(MYSQLI_ASSOC);
        if(empty($id)){
            http_response_code(401);
            echo json_encode([
                'status' => 'error',
                'message' => 'Token invalido'
            ]);
            exit;
        }
        http_response_code(200);
        return $id[0];
    }
    
    public function destroyToken($id)
    {
        $sessionId = implode($id);
        $userId = $this->query("SELECT user_id FROM {$this->table} WHERE id = {$sessionId}")->fetch_all(MYSQLI_ASSOC)[0];
        
        $isTokenExist = $this->getTokenById($userId);
        if (is_null($isTokenExist)){
            http_response_code(404);
            echo json_encode([
                'status' => 'error',
                'message' => 'Session no encontrada'
            ]);
            exit;
        }
        $userId = implode($userId);
        $this->query("DELETE FROM `personal_access_tokens` WHERE `user_id` = '{$userId}'");
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Session eliminada con exito'
        ]);
        exit;
    }
}