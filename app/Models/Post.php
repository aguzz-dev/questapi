<?php
namespace App\Models;

use App\Middleware\VerifyToken;
use Database;

class Post extends Database{
    protected $table = 'posts';

    public function find($id):array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = {$id}";
        return $this->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllPosts():array
    {
        $posts = [];
        $sql = "SELECT * FROM " . $this->table;
        $allPosts = $this->query($sql);

        foreach($allPosts as $post){
            array_push($posts, $post);
        }
        return $posts;
    }

    public function store($request)
    {
        VerifyToken::verifyToken($request['token']);
        $title  = $request['title'];
        $text   = $request['text'];
        $userId = $_SESSION['userId']; 
        $this->query("INSERT INTO {$this->table} (`title`, `text`, `user_id`) VALUES ('{$title}', '{$text}', '{$userId}')");
        $idPost = $this->dbConnection->insert_id;
        return $this->find($idPost);
    }

    public function update($request)
    {
        $post = $this->find($request['id']);
        if(!$post){
            http_response_code(422);
            return "Post no encontrado.";
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
        $post = $this->find($id);
        if(!$post){
            http_response_code(422);
            return "Post no encontrado.";
        }
        $sql = "DELETE FROM {$this->table} WHERE id = {$id}";
        $this->query($sql);
        return 'Post eliminado correctamente';
    }
}