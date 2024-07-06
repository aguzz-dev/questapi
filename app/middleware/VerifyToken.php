<?php

namespace App\Middleware;

use App\Helpers\getHeader;
use App\Models\PersonalAccessToken;

class VerifyToken
{
    public static function jwt()
    {
        (new PersonalAccessToken)->validateToken(getHeader::token());
    }
}
