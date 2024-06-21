<?php
namespace App\Models;

use app\Database;
use App\Traits\FindTrait;

class PublicPost extends Database
{
    use FindTrait;
    protected $table = 'public_posts';

    public function findById($id)
    {
        return $this->find($id);
    }

    public function makePublicPost($id)
    {
        $post = (new Post)->findById($id);
        if(!$post){
            throw new \Exception('Post no encontrado', 404);
        }
        $userId = (new Post)->findById($id)[0]['user_id'];
        $url    = random_int(1000000000, 9999999999).'-'.random_int(10000000, 99999999).'-'.random_int(10000, 99999);
        $this->query("UPDATE `posts` SET status = 1 WHERE id = {$id}");
        $this->query("INSERT INTO `{$this->table}` (`post_id`,`user_id`,`url`) VALUES ('{$id}', '{$userId}', '{$url}')");
        return $post;
    }

    public function makePrivatePost($id)
    {
        $publicPost = $this->find($id);
        $idPost = $publicPost[0]['post_id'];
        if(!$publicPost){
            throw new \Exception('Post no encontrado', 404);
        }
        $this->query("UPDATE `posts` SET `status` = '0' WHERE id = {$idPost}");
        $this->query("DELETE FROM {$this->table} WHERE id = {$id}");
        $post = (new Post)->findById($idPost);
        return $post;
    }
}
