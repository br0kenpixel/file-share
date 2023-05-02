<?php

if (!isset($_GET["file"])) {
    header("Location: /share_error.php");
}

require_once("components/db.php");
require_once("components/file_size.php");
use fileshare\components\DatabaseClient;
use fileshare\components\FileSize;

$dbClient = new DatabaseClient();
$file = $dbClient->get_file($_GET["file"]);

if ($file === false) {
    header("Location: /share_error.php");
}

$owner_name = $dbClient->get_username_by_id($file["owner"]);
$dbClient->increment_downloads($file["id"]);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . $file["name"]);
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . FileSize::get_size($owner_name, $file["name"]));
ob_clean();
flush();
readfile(FileSize::get_file_path($owner_name, $file["name"]));

?>