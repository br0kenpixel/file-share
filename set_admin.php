<?php

if (!isset($_GET["id"]) || !isset($_GET["admin"])) {
    header("Location: private/index.php");
    die();
}

if (empty($_GET["id"]) || (empty($_GET["admin"]) && $_GET["admin"] !== "0")) {
    header("Location: private/index.php");
    die();
}

if (!is_numeric($_GET["admin"])) {
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
$dbClient->set_admin($_GET["id"], intval($_GET["admin"]));
header("Location: /private/account.php?id=" . $_GET["id"]);
?>