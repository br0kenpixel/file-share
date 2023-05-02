<?php

namespace fileshare\components;

/**
 * Reads environment variables from `dotenv` files.
 *
 *
 * @copyright  2023 Fábián Varga
 * @license    GNU General Public License v3.0
 * @version    1.0.0
 */
class DotEnvReader
{
    private const ENV_FILE = __DIR__ . "/../.env";

    /**
     * Read the value af an environment variable defined in an `dotenv` file.
     *
     * @param string $var Name of the variable to read.
     * 
     * @throws \Exception If the dotenv file cannot be read.
     * @return string
     */
    public static function getvar(string $var): string
    {
        $fp = fopen(self::ENV_FILE, "r");
        if (!$fp) {
            throw new \Exception("Failed to read dotenv file.");
        }

        while (($buffer = fgets($fp, 256)) !== false) {
            if (!str_starts_with($buffer, $var)) {
                continue;
            }

            $value = explode('=', $buffer)[1];
            $value = substr($value, 0, strlen($value) - 1);
            fclose($fp);
            return $value;
        }

        fclose($fp);
        return null;
    }

    /**
     * Reads the value af an environment variable defined in an `dotenv` file
     * and converts it to a number.
     *
     * @param string $var Name of the variable to read.
     * 
     * @throws \Exception If the dotenv file cannot be read or the variable is not defined.
     * @return int
     */
    public static function getvar_as_int(string $var): int
    {
        $raw = self::getvar($var);

        if ($raw == null)
            throw new \Exception("Environment variable not found");
        return intval($raw);
    }
}

?>