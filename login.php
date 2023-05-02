<?php

session_start();
if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
    header("Location: private/index.php");
}

require_once("components/db.php");
use fileshare\components\DatabaseClient;

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $dbClient = new DatabaseClient();
    if ($dbClient->login($_POST["username"], $_POST["password"])) {
        session_start();
        $_SESSION["login"] = true;
        $_SESSION["username"] = $_POST["username"];
        $_SESSION["id"] = $dbClient->get_user_id($_POST["username"]);
        header("Location: private/index.php");
    } else {
        header("Location: login.php?error=true");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Share - Log in</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/login.css" rel="stylesheet">
</head>

<body class="no-select" data-bs-theme="dark">
    <?php require_once("parts/nav.php") ?>

    <?php
    if (isset($_GET["error"])) {
        ?>

        <br />
        <div class="container">
            <div class="alert alert-danger" role="alert">
                Invalid username or password.
            </div>
        </div>

        <?php
    }
    ?>

    <div class="container centered w-25">
        <form method="post" action="login.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>