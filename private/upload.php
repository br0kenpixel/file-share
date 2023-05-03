<?php
session_start();
if (!isset($_SESSION["login"]) && $_SESSION["login"] !== true) {
    header("Location: /index.php");
    die();
}

require_once("../components/db.php");
require_once("../components/file_size.php");
use fileshare\components\DatabaseClient;
use fileshare\components\FileSize;

$dbClient = new DatabaseClient();
$user_usage = FileSize::get_user_usage($_SESSION["username"]);
$user_limit = $dbClient->get_user_limit($_SESSION["id"]);
$can_upload = $user_usage < $user_limit;

if (isset($_FILES["file"])) {
    $file_name = basename($_FILES['file']['name']);
    $file_size = $_FILES['file']['size'];
    $file_tmp = $_FILES['file']['tmp_name'];

    if ($dbClient->file_exists($_SESSION["id"], $file_name)) {
        $_SESSION["upload_error"] = "A file already exists with that name.";
    } else if ($user_usage + $file_size >= $user_limit) {
        $_SESSION["upload_error"] = "You don't have enough space to store this file.";
    } else if (!$can_upload) {
        $_SESSION["upload_error"] = "You are not allowed to upload right now.";
    } else if (!move_uploaded_file($file_tmp, "../storage/" . $_SESSION["username"] . "/" . $file_name)) {
        $_SESSION["upload_error"] = "Failed to process file.";
    } else {
        $dbClient->add_file($_SESSION["id"], $file_name);
        $_SESSION["upload_ok"] = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Share - Upload</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/upload.css" rel="stylesheet">
</head>

<body class="no-select" data-bs-theme="dark">
    <?php require_once("../parts/priv_nav.php") ?>

    <br />

    <div class="container">
        <p class="h1">Upload file</p>
    </div>

    <br />

    <?php
    if (isset($_SESSION["upload_error"])) {
        ?>
        <div class="container">
            <div class="alert alert-danger" role="alert">
                <p class="h4">&#9940; Upload error</p>
                <p>
                    <?php echo $_SESSION["upload_error"]; ?>
                </p>
            </div>
        </div>
        <?php
    }
    unset($_SESSION["upload_error"]);
    ?>

    <?php
    if (isset($_SESSION["upload_ok"])) {
        ?>
        <div class="container">
            <div class="alert alert-success" role="alert">
                <p class="h4">&#9989; Upload successfull</p>
                <p>Your file has been uploaded successfully.</p>
            </div>
        </div>
        <?php
    }
    unset($_SESSION["upload_ok"]);
    ?>

    <?php
    if (!$can_upload) {
        ?>
        <div class="container">
            <div class="alert alert-danger" role="alert">
                <p class="h4">&#9940; Storage Limit Reached</p>
                <p>You can no longer upload files. Please delete some files to free up storage.</p>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="container">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="file" class="form-label">File</label>
                    <input class="form-control" type="file" id="file" name="file">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

            <br />

            <p><em>Upload limit:</em>
                <?php echo ini_get("post_max_size"); ?>
            </p>
        </div>
    <?php } ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>