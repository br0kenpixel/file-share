<?php
session_start();
if (!isset($_SESSION["login"]) && $_SESSION["login"] !== true) {
    header("Location: /index.php");
    die();
}

require_once("../components/db.php");
require_once("../components/formatter.php");
use fileshare\components\DatabaseClient;
use fileshare\components\Formatter;

$dbClient = new DatabaseClient();
session_start();

$display_id = $_SESSION["id"];
$display_username = $_SESSION["username"];
$display_email = $dbClient->get_user_email($display_id);
$display_storage_limit = $dbClient->get_user_limit($display_id);
$user_not_found = false;
$displaying_self = true;

if (isset($_GET["id"])) {
    if (empty($_GET["id"])) {
        header("Location: account.php");
        die();
    }

    if (!$_SESSION["is_admin"]) {
        header("Location: account.php");
        die();
    }

    $display_id = intval($_GET["id"]);
    $user = $dbClient->get_user($_GET["id"]);
    if ($user === false) {
        $user_not_found = true;
    } else {
        if ($user["id"] == $_SESSION["id"]) {
            header("Location: account.php");
        }

        $display_username = $user["username"];
        $display_email = $user["email"];
        $display_storage_limit = $dbClient->get_user_limit($display_id);
        $displaying_self = false;
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
            </p>
            <p><strong>E-mail: </strong><em>
                    <?php echo $display_email; ?>
            </p>
            <p><strong>Storage limit: </strong><em>
                    <?php echo Formatter::pretty_size($display_storage_limit); ?>
                    <?php if ($_SESSION["is_admin"]) {
                        ?>
                        <a href="" data-bs-toggle="modal" data-bs-target="#storageChangeModal">Change</a>
                        <?php
                    } ?>
            </p>
            <?php
            if ($displaying_self) {
                ?>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#passwordChangeModal">Change password</button>
                <?php
            } else {
                ?>
                <p style="color: red;"><em>You cannot change this user's password.</em></p>
                <?php
            }
            ?>
        </div>

        <br />
        <div class="container">
            <hr>
        </div>
        <br />

        <div class="container">
            <a href="<?php echo "/del_account.php?id=" . $display_id; ?>"><button type="button" class="btn btn-danger">
                    <?php echo $displaying_self ? "Delete my account" : "Delete account"; ?>
                </button></a>
            <?php if ($_SESSION["is_admin"]) {
                ?>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#accountModal">Manage
                    another account</button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                    data-bs-target="#adminChangeModal">Admin</button>
                <?php
            } ?>
        </div>
        <?php
    }
    ?>

    <?php
    if ($_SESSION["is_admin"]) {
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
        <div class="modal fade" id="storageChangeModal" tabindex="-1" aria-labelledby="storageChangeLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="storageChangeLabel">Change storage limit</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="get" action="/storage_update.php">
                            <input type="hidden" name="id" value="<?php echo $display_id; ?>">
                            <div class="mb-3">
                                <label for="limit" class="form-label">Storage limit (in bytes)</label>
                                <input type="number" class="form-control" id="limit" name="new"
                                    value="<?php echo $display_storage_limit; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Change</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="adminChangeModal" tabindex="-1" aria-labelledby="adminChangeLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="adminChangeLabel">Change administrator rights</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="get" action="/set_admin.php">
                            <input type="hidden" name="id" value="<?php echo $display_id; ?>">
                            <?php if ($dbClient->is_admin($display_id)) {
                                $button_text = "Revoke administrator rights";
                                ?>
                                <p style="color: green;">This user is an administrator.</p>
                                <input type="hidden" name="admin" value="0">
                                <?php
                            } else {
                                $button_text = "Give administrator rights";
                                ?>
                                <p style="color: red;">This user is not an administrator.</p>
                                <input type="hidden" name="admin" value="1">
                                <?php
                            } ?>
                            <button type="submit" class="btn btn-danger">
                                <?php echo $button_text; ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>

    <div class="modal fade" id="passwordChangeModal" tabindex="-1" aria-labelledby="passwordChangeLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="passwordChangeLabel">Change password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="/update_pass.php">
                        <input type="hidden" name="id" value="<?php echo $display_id; ?>">
                        <div class="mb-3">
                            <label for="currentPass" class="form-label">Current password</label>
                            <input type="password" class="form-control" id="currentPass" name="current">
                        </div>
                        <div class="mb-3">
                            <label for="newPass" class="form-label">New password</label>
                            <input type="password" class="form-control" id="newPass" name="new">
                        </div>
                        <button type="submit" class="btn btn-primary">Change</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>