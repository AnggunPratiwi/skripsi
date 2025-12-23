<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="favicon.ico" />
    <title>Login | FAHP TOPSIS</title>
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="assets/fontawesome-free/css/all.min.css" rel="stylesheet">
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/highcharts.js"></script>
    <style>
        .bg-primary,
        .btn-primary {
            background-color: #e83e8c;
            /* pink */
            background-image: linear-gradient(180deg, #e83e8c 10%, #d63384 100%);
            background-size: cover;
            border-color: #e83e8c;
        }
    </style>
</head>

<body class="bg-light h-100">
    <div class="container d-flex h-100">
        <div class="row align-items-center w-100">
            <div class="col-md-6 mx-auto">
                <div class="row">
                    <div class="col-6 bg-primary text-white text-center p-3">
                        <h5>SPK STUNTING</h5>
                        <div>
                            <img src="assets/img/logo.png" class="w-50" />
                        </div>
                    </div>
                    <div class="col-6">
                        <form class="form-signin" action="?m=login" method="post">
                            <?php if ($_POST) include 'aksi.php' ?>
                            <div class="mb-3">
                                <input type="text" class="form-control form-control-lg" placeholder="Username" name="user" autofocus autocomplete="off" />
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control form-control-lg" placeholder="Password" name="pass" />
                            </div>
                            <button class="btn btn-lg btn-primary w-100" type="submit"><i class="fa fa-right-to-bracket"></i> Masuk</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>