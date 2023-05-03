<?php
session_start();
$logged_in = isset($_SESSION["login"]) && $_SESSION["login"] === true;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Share - About</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/share.css" rel="stylesheet">
</head>

<body class="no-select" data-bs-theme="dark">
    <?php if ($logged_in) {
        require_once("parts/priv_nav.php");
    } else {
        require_once("parts/nav.php");
    } ?>

    <div class="container text-center">
        <div class="row">
            <div class="col">
                <p class="h1">Unathorized</p>
                <p><em>Your are not authorized to perform this operation.</em></p>

                <br />
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>