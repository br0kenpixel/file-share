<?php

if (!isset($_POST["id"]) || !isset($_POST["current"]) || !isset($_POST["new"])) {
    header("Location: private/index.php");
    die();
}

if (empty($_POST["id"]) || empty($_POST["current"]) || empty($_POST["new"])) {
    header("Location: private/index.php");
    die();
}

require_once("components/db.php");
use fileshare\components\DatabaseClient;

session_start();

if ($_SESSION["id"] != $_POST["id"] && !$_SESSION["is_admin"]) {
    header("Location: unauthorized.php");
    die();
}

$dbClient = new DatabaseClient();
if (!$dbClient->user_id_exists($_POST["id"])) {
    header("Location: private/index.php");
    die();
}

if (!$dbClient->update_password($_POST["id"], $_POST["current"], $_POST["new"])) {
    header("Location: unauthorized.php");
    die();
}
header("Location: private/logout.php");
?>