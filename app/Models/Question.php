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

    public function getQuestionsByPostId($postId)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE public_post_id = $postId")->fetch_all(MYSQLI_ASSOC);
    }

    public function store($request)
    {
        $publicPostId = $request['public_post_id'];
        $isPublicPostExist = (new PublicPost)->find($publicPostId);
        if (!$isPublicPostExist) {
            throw new \Exception('Post público no encontrado', 404);
        }
        $text = $request['text'];
        $this->query("INSERT INTO `{$this->table}` (public_post_id, text) VALUES ({$publicPostId}, '{$text}')");
        return [
            'id' => $publicPostId, 
            'text' => $text
        ];
    }

    public function answerQuestion($request)
    {
        VerifyToken::verifyToken($request['token']);
        $question = $this->find($request['id']);
        if(!$question){
            throw new \Exception('Pregunta no encontrada', 404);
        }
        $this->query("UPDATE {$this->table} SET status = 1 WHERE id = {$request['id']}");
        return $question;
    } 
}