<?php

if (!isset($_GET["id"])) {
    header("Location: private/index.php");
    die();
}

if (!isset($_SESSION["login"]) && $_SESSION["login"] !== true) {
    header("Location: /index.php");
    die();
}

if (empty($_GET["id"])) {
    header("Location: private/index.php");
    die();
}

require_once("components/db.php");
require_once("components/file_size.php");
use fileshare\components\DatabaseClient;
use fileshare\components\FileSize;

session_start();
if ($_SESSION["id"] != $_GET["id"] && !$_SESSION["is_admin"]) {
    header("Location: unauthorized.php");
    die();
}

$dbClient = new DatabaseClient();
if (!$dbClient->user_id_exists($_GET["id"])) {
    header("Location: private/index.php");
    die();
}
$username = $dbClient->get_username_by_id($_GET["id"]);
$dbClient->delete_user($_GET["id"]);

$user_dir = FileSize::get_user_data_dir($username);
foreach (scandir($user_dir) as $key => $file) {
    unlink($user_dir . "/" . $file);
}
rmdir($user_dir);
header("Location: private/logout.php");
?>