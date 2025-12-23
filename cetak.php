<?php include 'functions.php'; 
$mod = _get('m');
$periode = _get('periode');
?>

<!doctype html>
<html>

<head>
    <meta name="robots" content="noindex, nofollow" />
    <title>Cetak Laporan</title>
    <style>
        body {
            font-family: Verdana;
            font-size: 13px;
        }

        h1 {
            font-size: 16px;
            /* border-bottom: 4px double #000; */
            padding: 3px 0;
        }

        table {
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 3px;
        }

        .wrapper {
            margin: 0 auto;
            width: 980px;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="wrapper">
        <div style="border-bottom: 4px double black;">
            <div style="width: 20%; float: left;">
                <img src="assets/img/logo.png" height="100" />
            </div>
            <div style="width: 80%; float: right;text-align: center;">
                <h2 style="margin: 0; ">
                    KEMENTERIAN PENDIDIKAN, KEBUDAYAAN<br />
                    RISET, DAN TEKNOLOGI<br />
                    POLITEKNIK NEGERI SRIWIJAYA
                </h2>
                <p style="margin: 10px 0;">
                    Jalan Sriwijaya Negara Bukit Besar – Palembang 30139 Telepon (0711) 353414 <br />
                    Halaman : http://polsri.ac.id, Pos El : info@polsri.ac.id
                </p>
            </div>
            <div style="clear: both;"></div>
        </div>
        <?php

        if ($mod && is_file($mod . '_cetak.php')) {
            include $mod . '_cetak.php';
        } else {
            echo '<p style="color:red;text-align:center;">File cetak tidak ditemukan</p>';
        }
        ?><div>
            <div style="float: right; text-align: center; margin-top: 20px;">
                Kota Anda, <?= tgl_indo(date('Y-m-d')) ?><br />
                Mengetahui<br />
                <br />
                <br />
                <br />
                <br />
                Nama Pimpinan Anda
            </div>
        </div>
    </div>
</body>

</html>