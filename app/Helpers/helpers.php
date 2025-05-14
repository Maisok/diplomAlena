<?php

use Illuminate\Support\Str;

if (!function_exists('chat_preview')) {
    function chat_preview($message, $length = 50)
    {
        return Str::limit($message, $length);
    }
}