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
    <?php require_once("parts/nav.php") ?>

    <div class="container text-center">
        <div class="row">
            <div class="col">
                <p class="h1">Share</p>
                <p><em>$FILE$</em></p>

                <br />
            </div>
        </div>
    </div>

    <div class="centered">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col"><strong>Size:</strong></th>
                    <td>xxx kB</td>
                </tr>
                <tr>
                    <th scope="col"><strong>Kind:</strong></th>
                    <td>Plain text file</td>
                </tr>
                <tr>
                    <th scope="col"><strong>Owner:</strong></th>
                    <td>$OWNER$</td>
                </tr>
                <tr>
                    <th scope="col"><strong>Uploaded on:</strong></th>
                    <td>$UPLOAD_TIME$</td>
                </tr>
                <tr>
                    <th scope="col"><strong>Downloads:</strong></th>
                    <td>$DOWNLOAD_COUNT$</td>
                </tr>
            </thead>
        </table>
    </div>

    <div class="container text-center buttons">
        <div class="row">
            <div class="col">
                <button type="button" class="btn btn-primary btn-sm">&#11015;&#65039; Download</button>
                <button type="button" class="btn btn-secondary btn-sm">&#128206; Copy link</button>
                <button type="button" class="btn btn-danger btn-sm">&#10060; Delete</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>