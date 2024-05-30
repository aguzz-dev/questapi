<?php
namespace App\Models;

use App\Middleware\VerifyToken;
use Database;

class Question extends Database
{
    protected $table = 'questions';

    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = {$id}";
        return $this->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
    public function store($request)
    {
        $publicPostId = $request['public_post_id'];
        $isPublicPostExist = (new PublicPost)->find($publicPostId);
        if (!$isPublicPostExist) {
            http_response_code(422);
            return[
                'error' => 'Post público no encontrado.'
            ]; 
        }
        $text = $request['text'];
        $this->query("INSERT INTO `{$this->table}` (public_post_id, text) VALUES ({$publicPostId}, '{$text}')");
        http_response_code(200);
        return [
            'message' => 'Se ha agregado correctamente la pregunta.',
            'data' => [
                'idPost' => $publicPostId,
                'text' => $text
            ],
        ];
    }

    public function answerQuestion($request)
    {
        VerifyToken::verifyToken($request['token']);
        $isQuestionExist = $this->find($request['id']);
        if(!$isQuestionExist){
            http_response_code(422);
            return [
                'error' => 'Pregunta no encontrada.'
            ];
        }
        $this->query("UPDATE {$this->table} SET status = 1 WHERE id = {$request['id']}");
        http_response_code(200);
        return[
            'message' => 'Se ha actualizado el estado de la pregunta a Respondida.',
        ];

    } 
}