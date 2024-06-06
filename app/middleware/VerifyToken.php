<?php

namespace App\Middleware;

use App\Models\PersonalAccessToken;

class VerifyToken
{
    public static function verifyToken($token)
    {
        (new PersonalAccessToken)->validateToken($token);
    }
}
