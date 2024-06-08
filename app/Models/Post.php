<?php
namespace App\Models;

use App\Helpers\JsonResponse;
use Database;

class Post extends Database{
    protected $table = 'posts';

    public function find($id):array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = {$id}";
        return $this->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getPostId($publicPostId)
    {
        return implode($this->query("SELECT `id` FROM {$this->table} WHERE id = '{$publicPostId}'")->fetch_all(MYSQLI_ASSOC)[0]);
    }

    public function getAllPosts($userId):array
    {
        $posts = [];
        $sql = "SELECT * FROM " . $this->table. " WHERE user_id = '{$userId}'" ;
        $allPosts = $this->query($sql);

        foreach($allPosts as $post){
            array_push($posts, $post);
        }
        return $posts;
    }

    public function store($request)
    {
        $title  = $request->title;
        $text   = $request->text;
        $userId = (new PersonalAccessToken)->getIdByToken($request->token);
        $this->query("INSERT INTO {$this->table} (`title`, `text`, `user_id`) VALUES ('{$title}', '{$text}', '{$userId}')");
        $idPost = $this->dbConnection->insert_id;
        $postCreated = $this->find($idPost);
        return $postCreated;
    }

    public function update($request)
    {
        $post = $this->find($request->id);
        if(!$post){
            JsonResponse::send(false, 'Post no encontrado', 404);
        }
        $fields = [];
        foreach ($request as $key => $value) {
            $fields[] = "{$key} = '{$value}'";
        }
        $fields = implode(', ', $fields);
        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = {$request->id}";
        $this->query($sql);
        return $this->find($request['id']);
    }

    public function destroy($id)
    {
        $post = $this->find($id);
        if(!$post){
            JsonResponse::send(false, 'Post no encontrado', 404);
        }
        $sql = "DELETE FROM {$this->table} WHERE id = {$id}";
        $this->query($sql);
    }
}