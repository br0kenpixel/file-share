<?php

namespace fileshare\components;

class FileSize
{
    public static function get_file_path(string $user, string $file): string
    {
        return __DIR__ . "/../storage/" . $user . "/" . $file;
    }

    public static function get_size(string $user, string $file): int
    {
        $path = self::get_file_path($user, $file);
        return filesize($path);
    }

    public static function get_user_usage(string $username): int
    {
        $userdir = __DIR__ . "/../storage/" . $username;
        $usage = 0;

        foreach (scandir($userdir) as $i => $name) {
            if ($name == "." || $name == "..") {
                continue;
            }
            $usage += filesize($userdir . "/" . $name);
        }
        return $usage;
    }

    public static function calc_usage_percentage(int $cap, int $used): int
    {
        return intval(round(($used / $cap) * 100));
    }
}

?>