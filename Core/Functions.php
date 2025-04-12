<?php

namespace Core;

class Functions
{
    static function base_path($path): string
    {
        return BASE_PATH . $path;
    }

    static function dd($var)
    {
        header("HTTP/1.0 500");
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
        die();
    }
}
