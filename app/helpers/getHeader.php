<?php

namespace App\Helpers;

class GetHeader{
    public static function token()
    {
        return str_replace('Bearer ', '', (string)$_SERVER['HTTP_AUTHORIZATION']);
    }
}