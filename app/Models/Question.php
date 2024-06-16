<?php
namespace App\Models;

use App\Middleware\VerifyToken;
use app\Database;

class Question extends Database
{
    const NO_RESPONDIDA = 0;
    const RESPONDIDA = 1;
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
        $publicPostId = $request->id_post;
        $isPublicPostExist = (new Post)->getPostId($publicPostId);
        if (!$isPublicPostExist) {
            throw new \Exception('Post público no encontrado', 404);
        }
        $text = $request->text;
        $this->query("INSERT INTO `{$this->table}` (public_post_id, text) VALUES ({$publicPostId}, '{$text}')");
        return [
            'id' => $this->dbConnection->insert_id,
            'text' => $text,
            'id_post' => $publicPostId
        ];
    }

    public function answerQuestion($request)
    {
        VerifyToken::verifyToken($request->token);
        $question = $this->find($request->id)[0];
        if(!$question){
            throw new \Exception('Pregunta no encontrada', 404);
        }
        $this->query("UPDATE {$this->table} SET status = 1 WHERE id = {$request->id}");
        return [
            'id' => $question['id'],
            'text' => $question['text'],
            'post_id' => $question['public_post_id'],
            'status' => Question::RESPONDIDA
        ];
    } 
}