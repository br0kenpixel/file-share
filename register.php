<?php
session_start();
if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
    header("Location: private/index.php");
    die();
}

require_once("components/db.php");
use fileshare\components\DatabaseClient;

$register_error = false;
$register_ok = false;

if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"])) {
    $dbClient = new DatabaseClient();
    if (!$dbClient->add_user($_POST["username"], $_POST["email"], $_POST["password"])) {
        $register_error = true;
    } else {
        mkdir("storage/" . $_POST["username"]);
        $register_ok = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Share - Register</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/login.css" rel="stylesheet">
</head>

<body class="no-select" data-bs-theme="dark">
    <?php require_once("parts/nav.php"); ?>

    <?php
    if ($register_ok) {
        ?>
        <div class="container">
            <div class="alert alert-success" role="alert">
                <p class="h4">&#9989; Registration successfull</p>
                <p>Your account has been created successfully! You can now log in!</p>
            </div>
        </div>
        <?php
    }

    if ($register_error) {
        ?>
        <div class="container">
            <div class="alert alert-danger" role="alert">
                <p class="h4">&#9940; Registration error</p>
                <p>A user already exists with a given username. Please try again with a different username.</p>
            </div>
        </div>
        <?php
    }
    ?>

    <?php
    if (!$register_error && !$register_ok) {
        ?>
        <div class="container centered w-25">
            <form method="post" action="register.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="gdpr">
                    <label class="form-check-label" for="gdpr">I agree to our Terms & Privacy.</label>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <?php
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>