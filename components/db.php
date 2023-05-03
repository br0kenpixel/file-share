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

        $result = $result["is_admin"];

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
        $sql = "SELECT files.id, files.name, files.upload_time FROM files INNER JOIN users ON files.owner = users.id WHERE files.owner = :id";
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

    public function get_file(int $id): array|bool
    {
        $sql = "SELECT * from files WHERE id = :id";
        $statement = $this->connection->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        try {
            $statement->execute(["id" => $id]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }
        return $result;
    }

    public function get_username_by_id(int $id): string
    {
        $sql = "SELECT username from users WHERE id = :id";
        $statement = $this->connection->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        try {
            $statement->execute(["id" => $id]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }
        return $result["username"];
    }

    public function increment_downloads(int $file_id)
    {
        $sql = "UPDATE files SET download_count = download_count + 1 WHERE id = :id";
        $statement = $this->connection->prepare($sql);

        try {
            $statement->execute(["id" => $file_id]);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }
    }

    public function add_file(int $userid, string $file_name)
    {
        $sql = "INSERT INTO files (owner, name, upload_time) VALUES (?, ?, NOW())";
        $statement = $this->connection->prepare($sql);

        try {
            $statement->execute([$userid, $file_name]);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }
    }

    public function remove_file(int $id)
    {
        $sql = "DELETE FROM files WHERE id = :id";
        $statement = $this->connection->prepare($sql);

        try {
            $statement->execute(["id" => $id]);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }
    }

    public function file_exists(int $userid, string $file): bool
    {
        $sql = "SELECT COUNT(id) as count from files WHERE owner = :id AND name = :name";
        $statement = $this->connection->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        try {
            $statement->execute(["id" => $userid, "name" => $file]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }
        return $result["count"] === 1;
    }

    public function add_user(string $username, string $email, string $password, bool $is_admin = false): bool
    {
        if ($this->user_exists($username)) {
            return false;
        }

        $password = hash("sha256", $password);
        $sql = "INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)";
        $statement = $this->connection->prepare($sql);

        try {
            $statement->execute([$username, $email, $password, intval($is_admin)]);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }

        return true;
    }

    private function user_exists(string $username): bool
    {
        $sql = "SELECT COUNT(id) as count from users WHERE username = :username";
        $statement = $this->connection->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        try {
            $statement->execute(["username" => $username]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }
        return $result["count"] === 1;
    }

    public function get_user_email(int $id): string
    {
        $sql = "SELECT email from users WHERE id = :id";
        $statement = $this->connection->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        try {
            $statement->execute(["id" => $id]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }
        return $result["email"];
    }

    public function get_user(int $id): array|bool
    {
        $sql = "SELECT * from users WHERE id = :id";
        $statement = $this->connection->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        try {
            $statement->execute(["id" => $id]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }
        return $result;
    }

    public function user_id_exists(int $id): bool
    {
        $sql = "SELECT COUNT(id) as count from users WHERE id = :id";
        $statement = $this->connection->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

        try {
            $statement->execute(["id" => $id]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }
        return $result["count"] === 1;
    }

    public function delete_user(int $id)
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $statement = $this->connection->prepare($sql);

        try {
            $statement->execute(["id" => $id]);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }
    }

    public function update_password(int $userid, string $current, string $new): bool
    {
        if (!$this->login($this->get_username_by_id($userid), $current)) {
            return false;
        }

        $this->update_password_unchecked($userid, $new);
        return true;
    }

    private function update_password_unchecked(int $userid, string $new)
    {
        $new = hash("sha256", $new);
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $statement = $this->connection->prepare($sql);

        try {
            $statement->execute(["id" => $userid, "password" => $new]);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }
    }

    public function update_storage_limit(int $userid, int $new)
    {
        $sql = "UPDATE users SET storage_limit = :storage_limit WHERE id = :id";
        $statement = $this->connection->prepare($sql);

        try {
            $statement->execute(["id" => $userid, "storage_limit" => $new]);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            die();
        }
    }
}

?>