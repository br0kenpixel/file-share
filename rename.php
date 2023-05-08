<?php

if (!isset($_GET["file"]) || !isset($_GET["name"])) {
    header("Location: private/index.php");
    die();
}

if (empty($_GET["file"]) || (empty($_GET["name"]))) {
    header("Location: private/index.php");
    die();
}

require_once("components/db.php");
use fileshare\components\DatabaseClient;

session_start();

$dbClient = new DatabaseClient();
if ($dbClient->get_file_owner($_GET["file"]) !== $_SESSION["id"] && $_SESSION["is_admin"]) {
    header("Location: /unauthorized.php");
    die();
}

$oldname = $dbClient->get_file_name($_GET["file"]);
$dbClient->rename_file($_GET["file"], $_GET["name"]);
$userdir = "storage/" . $_SESSION["username"] . "/";
rename($userdir . $oldname, $userdir . $_GET["name"]);
header("Location: private/index.php");
?>