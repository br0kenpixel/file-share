<?php
session_start();
if (!isset($_SESSION["login"]) && $_SESSION["login"] !== true) {
    header("Location: /index.php");
}

require_once("../components/db.php");
use fileshare\components\DatabaseClient;

$dbClient = new DatabaseClient();
session_start();

$display_id = $_SESSION["id"];
$display_username = $_SESSION["username"];
$display_email = $dbClient->get_user_email($display_id);
$user_not_found = false;

if (isset($_GET["id"])) {
    if (empty($_GET["id"])) {
        header("Location: account.php");
    }

    if (!$_SESSION["is_admin"]) {
        header("Location: account.php");
    } else {
        $display_id = $_GET["id"];
        $user = $dbClient->get_user($_GET["id"]);
        if ($user === false) {
            $user_not_found = true;
        }

        if ($user["id"] == $_SESSION["id"]) {
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
            <a href="<?php echo "/del_account.php?id=" . $display_id; ?>"><button type="button"
                    class="btn btn-danger">Delete my account</button></a>
            <?php if ($_SESSION["is_admin"]) {
                ?>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#accountModal">Manage
                    another account</button>
                <?php
            } ?>
        </div>
        <?php
    }
    ?>

    <div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="accountModalLabel">Manage another account</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="get" action="account.php">
                        <div class="mb-3">
                            <label for="accountId" class="form-label">ID</label>
                            <input type="number" class="form-control" id="accountId" name="id">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>