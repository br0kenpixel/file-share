<?php

namespace fileshare\components;

require_once(__DIR__ . "/dotenv.php");

use PDO;
use fileshare\components\DotEnvReader;

/**
 * Comunicates with the database.
 *
 *
 * @copyright  2023 Fábián Varga
 * @license    GNU General Public License v3.0
 * @version    1.0.0
 */
class DatabaseClient
{
    private string $host;
    private int $port;
    private string $username;
    private string $password;
    private string $dbName;

    private $connection;

    public function __construct()
    {
        $this->host = DotEnvReader::getvar("DB_HOST");
        $this->port = DotEnvReader::getvar_as_int("DB_PORT");
        $this->username = DotEnvReader::getvar("DB_LOGIN");
        $this->password = DotEnvReader::getvar("DB_PASSWORD");
        $this->dbName = DotEnvReader::getvar("DB_NAME");

        try {
            $this->connection = new PDO(
                'mysql:charset=utf8;host=' . $this->host . ';dbname=' . $this->dbName . ";port=" . $this->port,
                $this->username,
                $this->password
            );
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }
    }

    public function login(string $username, string $password): bool
    {
        $password = hash("sha256", $password);
        $sql = "SELECT COUNT(id) as count FROM users WHERE username = :username AND password = :password";
        $statement = $this->connection->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        try {
            $statement->execute(["username" => $username, "password" => $password]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }

        $result = $result["count"];

        if ($result === 1) {
            return true;
        } else if ($result === 0) {
            return false;
        } else {
            throw new \Exception("Unexpected data type");
        }
    }

    public function get_user_id(string $username): int
    {
        $sql = "SELECT id FROM users WHERE username = :username";
        $statement = $this->connection->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        try {
            $statement->execute(["username" => $username]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }

        return $result["id"];
    }

    public function is_admin(mixed $id): bool
    {
        if (is_int($id)) {
            return $this->is_admin_by_id($id);
        } else if (is_string($id)) {
            return $this->is_admin_by_username($id);
        } else {
            throw new \Exception("Invalid identifier type, expected 'int' or 'string', got '" . gettype($id) . "'");
        }
    }

    private function is_admin_by_username(string $username): bool
    {
        $sql = "SELECT is_admin FROM users WHERE username = :username";
        $statement = $this->connection->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        try {
            $statement->execute(["username" => $username]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }

        if ($result === 1) {
            return true;
        } else if ($result === 0) {
            return false;
        } else {
            throw new \Exception("Unexpected type for 'is_admin'");
        }
    }

    private function is_admin_by_id(int $id): bool
    {
        $sql = "SELECT is_admin FROM users WHERE id = :id";
        $statement = $this->connection->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        try {
            $statement->execute(["id" => $id]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }

        if ($result === 1) {
            return true;
        } else if ($result === 0) {
            return false;
        } else {
            throw new \Exception("Unexpected type for 'is_admin'");
        }
    }

    public function get_user_file_count(int $id): int
    {
        $sql = "SELECT COUNT(files.id) as count FROM files INNER JOIN users ON files.owner = users.id WHERE files.owner = :id";
        $statement = $this->connection->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        try {
            $statement->execute(["id" => $id]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }

        return $result["count"];
    }

    /**
     * Return a user's storage capacity limit in bytes.
     *
     * @param int $id ID of the user
     * 
     * @return int
     */
    public function get_user_limit(int $id): int
    {
        $sql = "SELECT storage_limit FROM users WHERE id = :id";
        $statement = $this->connection->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        try {
            $statement->execute(["id" => $id]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }

        return $result["storage_limit"];
    }

    public function get_user_files(int $id): array
    {
        $sql = "SELECT files.name, files.upload_time FROM files INNER JOIN users ON files.owner = users.id WHERE files.owner = :id";
        $statement = $this->connection->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        try {
            $statement->execute(["id" => $id]);
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }
        return $result;
    }
}

?>