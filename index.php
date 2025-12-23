<?php
include 'functions.php';
if (!_session('login'))
    header('location:login.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/png" href="assets/img/favicon.png">

    <title> FAHP TOPSIS</title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="assets/vendor/select2/css/select2.min.css" rel="stylesheet">
    <link href="assets/vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css" rel="stylesheet">
    <style>
        .sidebar .sidebar-brand {
            font-size: 0.8rem;
        }

        .navbar-nav1 {
            position: fixed;
            width: 100%;
            margin: 0 0 405px 0;
        }

        @media(min-width:768px) {
            #content-wrapper {
                position: inherit;
                margin: 0 0 0 224px;
                /* padding: 52px 0 0 30px; */
                /* for fixed menu*/
                /*padding: 0 30px;*/
                border-left: 1px solid #e7e7e7;
            }
        }

        .bg-gradient-primary {
            background-color: #e83e8c;
            /* pink */
            background-image: linear-gradient(180deg, #e83e8c 10%, #d63384 100%);
            background-size: cover;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar fixed-top sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="?">
                <div class="sidebar-brand-icon">
                    <img src="assets/img/logo.png" height="60" />
                </div>
                <div class="sidebar-brand-text mx-3">FAHP TOPSIS Stunting</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->


            <?php if (_session('login')) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="?m=home">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span></a>
                </li>
                <li class="nav-item" <?= is_hidden('alternatif') ?>>
                    <a class="nav-link" href="?m=alternatif">
                        <i class="fas fa-fw fa-bell"></i>
                        <span>Balita</span></a>
                </li>
                <li class="nav-item" <?= is_hidden('kriteria') ?>>
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#c1"
                        aria-expanded="true" aria-controls="c1">
                        <i class="fas fa-fw fa-cog"></i>
                        <span>Kriteria</span>
                    </a>
                    <div id="c1" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="?m=kriteria">Kriteria</a>
                            <a class="collapse-item" href="?m=rel_kriteria" <?= is_hidden('rel_kriteria') ?>>Bobot Kriteria</a>
                        </div>
                    </div>
                </li>
                <li class="nav-item" <?= is_hidden('rel_alternatif') ?>>
                    <a class="nav-link" href="?m=rel_alternatif">
                        <i class="fas fa-fw fa-bell"></i>
                        <span>Penilaian</span></a>
                </li>
                <!-- <li class="nav-item" <?= is_hidden('hitung') ?>>
                    <a class="nav-link" href="?m=hitung">
                        <i class="fas fa-fw fa-signal"></i>
                        <span>Perhitungan</span></a>
                </li> -->
                <li class="nav-item" <?= is_hidden('laporan') ?>>
                    <a class="nav-link" href="?m=laporan">
                        <i class="fas fa-fw fa-calendar"></i>
                        <span>Laporan</span></a>
                </li>
                <!-- 
                <li class="nav-item" <?= is_hidden('hasil') ?>>
                    <a class="nav-link" href="?m=hasil">
                        <i class="fas fa-fw fa-calendar"></i>
                        <span>Hasil</span></a>
                </li>
                <li class="nav-item" <?= is_hidden('profil') ?>>
                    <a class="nav-link" href="?m=profil">
                        <i class="fas fa-fw fa-calendar"></i>
                        <span>Profil</span></a>
                </li> -->
            <?php else : ?>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="?m=home">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Home</span></a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link" href="?m=login">
                        <i class="fas fa-fw fa-sign-in-alt"></i>
                        <span>Login</span></a>
                </li>
            <?php endif ?>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content" style="min-height: 600px;">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <?php if (_session('login')) : ?>
                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= _session('login') ?> (<?= _session('level') ?>)</span>
                                    <img class="img-profile rounded-circle" src="assets/img/undraw_profile.svg">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="?m=password">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Password
                                    </a>
                                    <a class="dropdown-item" href="aksi.php?act=logout">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </a>
                                </div>
                            </li>
                        <?php endif ?>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <?php
                    if (!_session('login') && !in_array($mod, array('', 'home', 'login', 'konsultasi', 'kriteria', 'alternatif')))
                        $mod = 'login';

                    if (file_exists($mod . '.php'))
                        include $mod . '.php';
                    else
                        include 'home.php';
                    ?>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>FAHP TOPSIS &copy; <?= date('Y') ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>
    <script src="assets/vendor/select2/js/select2.min.js"></script>

    <script>
        $(function() {
            $('#sidebarToggle').click(function(e) {
                $('#content-wrapper').css('margin-left', $('#accordionSidebar').width());
            });
        })
    </script>
    <script>
        $(function() {
            $('select').select2({
                'theme': 'bootstrap4',
            });
        })
    </script>
</body>

</html>