<?php

namespace App\Middleware;

use App\Models\PersonalAccessToken;

class VerifyToken
{
    public static function jwt($token)
    {
        (new PersonalAccessToken)->validateToken($token);
    }
}
