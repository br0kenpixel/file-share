<?php
session_start();
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
                <tr>
                    <th scope="row">sample.txt</th>
                    <td>1 KB</td>
                    <td>Plain text file</td>
                    <td>12.09.2011</td>
                    <td class="vallign">
                        <button type="button" class="btn btn-primary btn-sm">&#11015;&#65039; Download</button>
                        <button type="button" class="btn btn-success btn-sm">&#128206; Share link</button>
                        <button type="button" class="btn btn-danger btn-sm">&#10060; Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <p>Files count: ?</p>
        <p>Storage usage: </p>
        <div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="25" aria-valuemin="0"
            aria-valuemax="100">
            <div class="progress-bar" style="width: 25%">25%</div>
        </div>
        <p>Storage limit: ?</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>