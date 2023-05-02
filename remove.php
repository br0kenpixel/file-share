<?php

if (!isset($_GET["file"])) {
    header("Location: /share_error.php");
}

if (!isset($_SESSION["login"]) && $_SESSION["login"] !== true) {
    header("Location: /index.php");
}

if (empty($_GET["file"])) {
    header("Location: /index.php");
}

require_once("components/db.php");
require_once("components/file_size.php");
use fileshare\components\DatabaseClient;
use fileshare\components\FileSize;

session_start();
$dbClient = new DatabaseClient();
$file = $dbClient->get_file($_GET["file"]);

if ($file === false) {
    header("Location: /share_error.php");
}

if ($file["owner"] != $_SESSION["id"]) {
    header("Location: /share_error.php");
}

$dbClient->remove_file($file["id"]);
unlink("storage/" . $_SESSION["username"] . "/" . $file["name"]);
header("Location: private/index.php");
?>