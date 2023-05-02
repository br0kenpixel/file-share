<?php

namespace fileshare\components;

class FileSize
{
    public static function get_size(string $user, string $file): int
    {
        $path = __DIR__ . "/../storage/" . $user . "/" . $file;
        return filesize($path);
    }
}

?>