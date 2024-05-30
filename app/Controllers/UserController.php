<?php
namespace App\Controllers;

use App\Models\User;

class UserController
{
    public function store()
    {
        $request = $_POST;
        $res = (new User)->store($request);
        return $res;
    }

    public function update()
    {
        parse_str(file_get_contents("php://input"),$request);
        $res = (new User)->update($request);
        return $res;
    }

    public function destroy()
    {
        parse_str(file_get_contents("php://input"),$user);
        $res = (new User)->destroy($user['id']);
        return $res;
    }
}