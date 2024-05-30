<?php
namespace App\Models;

use Database;

class PublicPost extends Database
{
    protected $table = 'public_posts';

    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = {$id}";
        return $this->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
    public function makePublicPost($id)
    {
        $post = (new Post)->find($id);
        if(!$post){
            http_response_code(422);
            return [
                'error' => 'Post no encontrado.',
                'status_code' => 422
            ];
        }
        $userId = $_SESSION['userId'];
        $url    = random_int(1000000000, 9999999999).'-'.random_int(10000000, 99999999).'-'.random_int(10000, 99999);
        $this->query("UPDATE `posts` SET status = 1 WHERE id = {$id}");
        $this->query("INSERT INTO `{$this->table}` (`post_id`,`user_id`,`url`) VALUES ('{$id}', '{$userId}', '{$url}')");
        http_response_code(200);
        return [
            'message' => 'Post publicado correctamente.',
            'status_code' => 200,
            'data' => $post
        ];
    }

    public function makePrivatePost($id)
    {
        $isPostExist = $this->find($id);
        if(!$isPostExist){
            http_response_code(422);
            return [
                'error' => 'Post no encontrado.',
                'status_code' => 422
            ];
        }
        $post = (new Post)->find($id);
        $this->query("UPDATE `posts` SET status = 0 WHERE id = {$id}");
        $this->query("DELETE FROM {$this->table} WHERE id = {$id}");
        http_response_code(200);
        return[
            'message' => 'El post se ha ocultado correctamente.',
            'status_code' => 200,
            'data' => $post
        ];
    }
}
