<?php
namespace App\Models;

use App\Helpers\JsonResponse;
use app\Database;
use App\Traits\FindTrait;

class Post extends Database
{
    use FindTrait;
    protected $table = 'posts';

    const DEFAULT_ASSET = 0;

    public function findById($id)
    {
        return $this->find($id);
    }

    public function getPostId($publicPostId)
    {
        return $this->query("SELECT `id` FROM {$this->table} WHERE id = '{$publicPostId}'")->fetch_all(MYSQLI_ASSOC);
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
        $userId = (new PersonalAccessToken)->getIdByToken($request->token);
        $this->query("INSERT INTO {$this->table} (`title`, `asset_id`, `user_id`) VALUES ('{$title}', '{$request->asset_id}', '{$userId}')");
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
        unset($fields[0]);
        $fields = implode(', ', $fields);
        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = {$request->id}";
        $this->query($sql);
        return $this->find($request->id);
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