<?php

if (!isset($_GET["id"]) || !isset($_GET["new"])) {
    header("Location: private/index.php");
    die();
}

if (empty($_GET["id"]) || empty($_GET["new"])) {
    header("Location: private/index.php");
    die();
}

if (!is_numeric($_GET["new"])) {
    header("Location: private/index.php");
    die();
}

require_once("components/db.php");
use fileshare\components\DatabaseClient;

session_start();

if (!$_SESSION["is_admin"]) {
    header("Location: unauthorized.php");
    die();
}

$dbClient = new DatabaseClient();
$dbClient->update_storage_limit($_GET["id"], $_GET["new"]);
header("Location: /private/account.php");
?>