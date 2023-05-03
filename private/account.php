<?php
session_start();
if (!isset($_SESSION["login"]) && $_SESSION["login"] !== true) {
    header("Location: /index.php");
}

require_once("../components/db.php");
require_once("../components/file_size.php");
require_once("../components/formatter.php");
use fileshare\components\DatabaseClient;
use fileshare\components\FileSize;
use fileshare\components\Formatter;

$dbClient = new DatabaseClient();
session_start();

$display_id = $_SESSION["id"];
$display_username = $_SESSION["username"];
$display_email = $dbClient->get_user_email($display_id);
$user_not_found = false;

if (isset($_GET["id"])) {
    if (!$_SESSION["is_admin"]) {
        header("Location: account.php");
    } else {
        $display_id = $_GET["id"];
        $user = $dbClient->get_user($_GET["id"]);
        if ($user === false) {
            $user_not_found = true;
        }

        if ($user["id"] == $display_id) {
            header("Location: account.php");
        }

        $display_username = $user["username"];
        $display_email = $user["email"];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Share - Account</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/private_index.css" rel="stylesheet">
</head>

<body class="no-select" data-bs-theme="dark">
    <?php require_once("../parts/priv_nav.php"); ?>

    <br />

    <div class="container">
        <p class="h1">Account management</p>
    </div>

    <br />

    <?php
    if ($user_not_found) {
        ?>
        <div class="container">
            <div class="alert alert-danger" role="alert">
                <p class="h4">&#9940; Could not find account</p>
                <p>Account with ID <em>
                        <?php echo $display_id; ?>
                    </em> does not exist.</p>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="container">
            <p><strong>ID: </strong><em>
                    <?php echo $display_id; ?>
                </em></p>
            <p><strong>Username: </strong><em>
                    <?php echo $display_username; ?>
                </em> <a href="">Change</a></p>
            <p><strong>E-mail: </strong><em>
                    <?php echo $display_email; ?>
                </em> <a href="">Change</a></p>
            <button type="button" class="btn btn-primary">Change password</button>
        </div>

        <br />
        <div class="container">
            <hr>
        </div>
        <br />

        <div class="container">
            <button type="button" class="btn btn-danger">Delete my account</button>
            <?php if ($_SESSION["is_admin"]) {
                ?>
                <button type="button" class="btn btn-warning">Manage another account</button>
                <?php
            } ?>
        </div>
        <?php
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>