<?php
session_start();
if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
    header("Location: private/index.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Share - Home</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/index.css" rel="stylesheet">
</head>

<body class="no-select" data-bs-theme="dark">
    <?php require_once("parts/nav.php"); ?>

    <div class="container text-center">
        <br />

        <div class="row justify-content-md-center">
            <div class="col-md-auto">
                <p class="h1 fw-bold white-text">FileShare</p>
                <p class="h4 fst-italic white-text">The simplest file sharing service!</p>

                <hr />

                <div class="container text-center">
                    <div class="row">
                        <div class="col step white-text">
                            <button type="button" class="btn btn-primary">1. Register</button>
                        </div>
                        <div class="col step white-text">
                            <button type="button" class="btn btn-warning">2. Login</button>
                        </div>
                        <div class="col step white-text">
                            <button type="button" class="btn btn-info">3. Upload!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br />
    </div>

    <br />

    <div class="container">
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed custom-accordion" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        How does the service work?
                    </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        FileShare allows you to upload your files and create an easily shareable link which you or your
                        friends can
                        use to download the files.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                        Can I upload anonymously?
                    </button>
                </h2>
                <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        No. Files are attached to your account, so you need to be registered and logged in.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                        What is the maximum file size?
                    </button>
                </h2>
                <div id="flush-collapseThree" class="accordion-collapse collapse"
                    data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        The maximum file size is 20MB.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                        How many files can I upload?
                    </button>
                </h2>
                <div id="flush-collapseFour" class="accordion-collapse collapse"
                    data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        There is no restriction on this. You can upload as many files as you want, as long as you don't
                        exceed a storage limit.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                        How much storage do I have?
                    </button>
                </h2>
                <div id="flush-collapseFive" class="accordion-collapse collapse"
                    data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        All accounts are limited to 20MB.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>