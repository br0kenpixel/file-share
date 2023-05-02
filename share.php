<?php
if (!isset($_GET["file"])) {
    header("Location: /share_error.php");
}

if (empty($_GET["file"])) {
    header("Location: /share_error.php");
}

require_once("components/db.php");
require_once("components/file_size.php");
require_once("components/formatter.php");
use fileshare\components\DatabaseClient;
use fileshare\components\FileSize;
use fileshare\components\Formatter;

session_start();
$dbClient = new DatabaseClient();
$file = $dbClient->get_file($_GET["file"]);

if ($file === false) {
    header("Location: /share_error.php");
}

$owner_name = $dbClient->get_username_by_id($file["owner"]);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Share -
        <?php echo $file["name"] ?>
    </title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/share.css" rel="stylesheet">
</head>

<body class="no-select" data-bs-theme="dark">
    <?php require_once("parts/nav.php") ?>

    <div class="container text-center">
        <div class="row">
            <div class="col">
                <p class="h1">Share</p>
                <p><em>
                        <?php echo $file["name"] ?>
                    </em></p>

                <br />
            </div>
        </div>
    </div>

    <div class="centered">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col"><strong>Size:</strong></th>
                    <td>
                        <?php echo Formatter::pretty_size(FileSize::get_size($owner_name, $file["name"])); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="col"><strong>Kind:</strong></th>
                    <td>
                        <?php echo Formatter::get_file_kind($file["name"]); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="col"><strong>Owner:</strong></th>
                    <td>
                        <?php echo $owner_name . " (" . $file["owner"] . ")"; ?>
                    </td>
                </tr>
                <tr>
                    <th scope="col"><strong>Uploaded on:</strong></th>
                    <td>
                        <?php echo $file["upload_time"]; ?>
                    </td>
                </tr>
                <tr>
                    <th scope="col"><strong>Downloads:</strong></th>
                    <td>
                        <?php echo $file["download_count"]; ?>
                    </td>
                </tr>
            </thead>
        </table>
    </div>

    <div class="container text-center buttons">
        <div class="row">
            <div class="col">
                <a href="<?php echo "/download.php?file=" . $file["id"]; ?>"><button type="button"
                        class="btn btn-primary btn-sm">&#11015;&#65039; Download</button></a>
                <button id="copy-btn" type="button" class="btn btn-secondary btn-sm" onclick="copyLink()">&#128206; Copy
                    link</button>
                <?php
                if (isset($_SESSION["login"]) && $_SESSION["login"] === true && $_SESSION["id"] == $file["owner"]) {
                    ?>
                    <a href="<?php echo "/remove.php?file=" . $file["id"]; ?>"><button type="button"
                            class="btn btn-danger btn-sm">&#10060; Delete</button></a>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyLink() {
            var button = document.getElementById("copy-btn");
            var button_text = button.textContent;
            navigator.clipboard.writeText(window.location.href);

            button.textContent = "Copied!";
            setTimeout(function () {
                button.textContent = button_text;
            }, 1000);
        }
    </script>
</body>

</html>