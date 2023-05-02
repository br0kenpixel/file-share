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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Share - Private Panel</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/private_index.css" rel="stylesheet">
</head>

<body class="no-select" data-bs-theme="dark">
    <?php require_once("../parts/priv_nav.php"); ?>

    <br />

    <div class="container">
        <p class="h1">Welcome,
            <?php echo $_SESSION["username"] ?>!
        </p>
    </div>

    <br />

    <div class="container" style="display: none;">
        <p>You currently don't have any files.</p>
    </div>

    <div class="container">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Size</th>
                    <th scope="col">Kind</th>
                    <th scope="col">Uploaded on</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($dbClient->get_user_files($_SESSION["id"]) as $key => $value) {
                    ?>
                    <tr>
                        <th scope="row">
                            <?php echo $value["name"]; ?>
                        </th>
                        <td>
                            <?php echo Formatter::pretty_size(FileSize::get_size($_SESSION["username"], $value["name"])); ?>
                        </td>
                        <td>
                            <?php echo Formatter::get_file_kind($value["name"]); ?>
                        </td>
                        <td>
                            <?php echo $value["upload_time"]; ?>
                        </td>
                        <td class="vallign">
                            <a href="<?php echo "/download.php?file=" . $value["id"]; ?>"><button type="button"
                                    class="btn btn-primary btn-sm">&#11015;&#65039; Download</button></a>
                            <button type="button" class="btn btn-success btn-sm">&#128206; Share link</button>
                            <a href="<?php echo "/remove.php?file=" . $value["id"]; ?>"><button type="button"
                                    class="btn btn-danger btn-sm">&#10060; Delete</button></a>

                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <?php
        $cap = $dbClient->get_user_limit($_SESSION["id"]);
        $usage = FileSize::get_user_usage($_SESSION["username"]);
        $usage_percentage = FileSize::calc_usage_percentage($cap, $usage);
        ?>

        <p>Files count:
            <?php echo $dbClient->get_user_file_count($_SESSION["id"]) ?>
        </p>
        <p>Storage usage:
            <?php echo Formatter::pretty_size($usage); ?>
        </p>

        <div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="25" aria-valuemin="0"
            aria-valuemax="100">
            <div class="progress-bar" style="width: <?php echo $usage_percentage; ?>%">
                <?php echo $usage_percentage; ?>%
            </div>
        </div>
        <p>Storage limit:
            <?php echo Formatter::pretty_size($cap); ?>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>