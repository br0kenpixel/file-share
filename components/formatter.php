<?php

namespace fileshare\components;

class Formatter
{
    private const FILE_EXTENSIONS = [
        "txt" => "Plain Text File",
        "doc" => "Word Document",
        "docx" => "Word Document",
        "bin" => "Binary file",
        "hex" => "HEX file",
        "exe" => "Windows Executable",
        "mp3" => "MP3 audio",
        "wav" => "WAVE audio",
        "c" => "C source",
        "h" => "C header",
        "rs" => "Rust source",
        "zip" => "ZIP Archive",
        "tar" => "Tarball",
        "7z" => "7-Zip Archive"
    ];
    public static function pretty_size(int $amount): string
    {
        if ($amount > 1000000) {
            $amount /= 1000000;
            return number_format($amount, 2) . " MB";
        } else if ($amount > 1000) {
            $amount /= 1000;
            return number_format($amount, 2) . " kB";
        } else {
            return strval($amount) . " bytes";
        }
    }

    public static function get_file_kind(string $name): string
    {
        $ext = self::get_file_extension($name);
        if (isset(self::FILE_EXTENSIONS[$ext])) {
            return self::FILE_EXTENSIONS[$ext];
        }

        return "Unknown";
    }

    private static function get_file_extension(string $name): string
    {
        $index = 0;
        for ($i = 0; $i < strlen($name); $i++) {
            if ($name[$i] == '.')
                $index = $i;
        }

        return strtolower(substr($name, $index + 1));
    }
}

?>