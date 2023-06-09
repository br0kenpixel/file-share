<?php

if (!isset($_GET["file"])) {
    header("Location: /share_error.php");
    die();
}

session_start();
if (!isset($_SESSION["login"]) && $_SESSION["login"] !== true) {
    header("Location: /index.php");
    die();
}

if (empty($_GET["file"])) {
    header("Location: /index.php");
    die();
}

require_once("components/db.php");
use fileshare\components\DatabaseClient;

$dbClient = new DatabaseClient();
$file = $dbClient->get_file($_GET["file"]);

if ($file === false) {
    header("Location: /share_error.php");
    die();
}

if ($file["owner"] != $_SESSION["id"]) {
    header("Location: /share_error.php");
    die();
}

$dbClient->remove_file($file["id"]);
unlink("storage/" . $_SESSION["username"] . "/" . $file["name"]);
header("Location: private/index.php");
?>