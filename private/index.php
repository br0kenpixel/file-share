<?php
session_start();
if (!isset($_SESSION["login"]) && $_SESSION["login"] !== true) {
    header("Location: /index.php");
    die();
}

require_once("../components/db.php");
require_once("../components/file_size.php");
require_once("../components/formatter.php");
use fileshare\components\DatabaseClient;
use fileshare\components\FileSize;
use fileshare\components\Formatter;

$dbClient = new DatabaseClient();
$root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
$file_count = $dbClient->get_user_file_count($_SESSION["id"]);
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

    <?php if ($file_count === 0) {
        ?>
        <div class="container">
            <p>You currently don't have any files. Maybe upload some?</p>
        </div>
        <?php
    } else { ?>
        <?php if ($_SESSION["is_admin"]) {
            ?>
            <div class="container">
                <hr>
                <p>Administration:</p>

                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#accountModal">Lookup
                    account</button>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#fileModal">Lookup
                    file</button>
                <hr>
            </div>

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

            <div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="fileModalLabel">Manage share</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="get" action="/share.php">
                                <div class="mb-3">
                                    <label for="fileId" class="form-label">File ID</label>
                                    <input type="number" class="form-control" id="fileId" name="file">
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php
        } ?>

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
                                <a class="filename-text" style="text-decoration: none; color: inherit;">
                                    <?php echo $value["name"]; ?>
                                </a>
                                <input class="editable-filename" type="text" value="<?php echo $value["name"]; ?>"
                                    id="<?php echo $value["id"]; ?>" style="display: none; width: 100%;">
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
                                <a href="<?php echo $root . "share.php?file=" . $value["id"] ?>"><button id="copy-btn"
                                        type="button" class="btn btn-success btn-sm">&#128206; Share link</button></a>
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
                <?php echo $file_count; ?>
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
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var filename_text = document.querySelectorAll(".filename-text");
        var editable_filename = document.querySelectorAll(".editable-filename");

        filename_text.forEach(element => {
            element.addEventListener("dblclick", (_) => {
                var editable_name = element.parentElement.querySelectorAll(".editable-filename")[0];

                element.style.display = "none";
                editable_name.style.display = "block";
                editable_name.focus();
            });
        });

        editable_filename.forEach(element => {
            element.addEventListener("keyup", ({ key }) => {
                if (key !== "Enter") {
                    return;
                }
                var static_filename = element.parentElement.querySelectorAll(".filename-text")[0];

                element.style.display = "none";
                static_filename.style.display = "block";
                document.location = "/rename.php?file=" + element.id + "&name=" + encodeURIComponent(element.value);
            });
        });

        document.addEventListener("click", (event) => {
            var target = event.target;

            if (target.tagName === "INPUT") {
                return;
            }

            filename_text.forEach(element => {
                element.style.display = "block";
            });
            editable_filename.forEach(element => {
                element.style.display = "none";
            });
        });
    </script>
</body>

</html>