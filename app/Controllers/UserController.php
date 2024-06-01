<?php
namespace App\Controllers;

use App\Models\User;

class UserController
{
    public function store()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        $res = (new User)->store($request);
        return $res;
    }

    public function update()
    {
        $request = json_decode(file_get_contents("php://input"), true);
        $res = (new User)->update($request);
        return $res;
    }

    public function destroy()
    {
        $user = json_decode(file_get_contents("php://input"), true);
        $res = (new User)->destroy($user['id']);
        return $res;
    }
}