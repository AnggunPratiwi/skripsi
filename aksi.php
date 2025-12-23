<?php
require_once 'functions.php';

if ($mod == 'login') {
    $user = esc_field($_POST['user']);
    $pass = esc_field($_POST['pass']);

    $row = $db->get_row("SELECT * FROM tb_user WHERE user='$user' AND pass='$pass'");
    if ($row) {
        $_SESSION['login'] = $row->user;
        $_SESSION['ID'] = $row->id_user;
        $_SESSION['level'] = $row->level;
        redirect_js("index.php");
    } else {
        print_msg("Salah kombinasi username dan password.");
    }
} else if ($mod == 'password') {
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    $pass3 = $_POST['pass3'];

    $tb = $_SESSION['level'] == 'alternatif' ? 'tb_alternatif' : 'tb_user';
    $row = $db->get_row("SELECT * FROM $tb WHERE user='$_SESSION[login]' AND pass='$pass1'");

    if ($pass1 == '' || $pass2 == '' || $pass3 == '')
        print_msg('Field bertanda * harus diisi.');
    elseif (!$row)
        print_msg('Password lama salah.');
    elseif ($pass2 != $pass3)
        print_msg('Password baru dan konfirmasi password baru tidak sama.');
    else {
        $db->query("UPDATE $tb SET pass='$pass2' WHERE user='$_SESSION[login]'");
        print_msg('Password berhasil diubah.', 'success');
    }
} elseif ($act == 'logout') {
    unset($_SESSION['login']);
    header("location:index.php?m=login");
}

/** alternatif **/
elseif ($mod == 'alternatif_tambah') {
    $kode_alternatif = $_POST['kode_alternatif'];
    $nama_balita = $_POST['nama_balita'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $nama_ortu = $_POST['nama_ortu'];

    if ($kode_alternatif == '' || $nama_balita == '' || $jenis_kelamin == '' || $tanggal_lahir == '' || $nama_ortu == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    elseif ($db->get_results("SELECT * FROM tb_alternatif WHERE kode_alternatif='$kode_alternatif'"))
        print_msg("Kode sudah ada!");
    else {
        $db->query("INSERT INTO tb_alternatif(kode_alternatif, jenis_kelamin, tanggal_lahir, nama_ortu, nama_balita) VALUES ('$kode_alternatif', '$jenis_kelamin', '$tanggal_lahir', '$nama_ortu', '$nama_balita')");
        set_msg('Data berhasil ditambah');
        redirect_js("index.php?m=alternatif");
    }
} elseif ($mod == 'alternatif_ubah') {
    $nama_balita = $_POST['nama_balita'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $nama_ortu = $_POST['nama_ortu'];
    $pass = $_POST['pass'];

    if ($nama_balita == '' || $jenis_kelamin == '' || $tanggal_lahir == '' || $nama_ortu == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    else {
        $db->query("UPDATE tb_alternatif SET nama_balita='$nama_balita', jenis_kelamin='$jenis_kelamin', tanggal_lahir='$tanggal_lahir', nama_ortu='$nama_ortu' WHERE kode_alternatif='$_GET[ID]'");
        set_msg('Data berhasil diubah');
        redirect_js("index.php?m=alternatif");
    }
} elseif ($act == 'alternatif_hapus') {
    $db->query("DELETE FROM tb_alternatif WHERE kode_alternatif='$_GET[ID]'");
    set_msg('Data berhasil dihapus');
    header("location:index.php?m=alternatif");
}

/** kriteria */
elseif ($mod == 'kriteria_tambah') {
    $kode_kriteria = $_POST['kode_kriteria'];
    $nama_kriteria = $_POST['nama_kriteria'];

    if ($kode_kriteria == '' || $nama_kriteria == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    elseif ($db->get_results("SELECT * FROM tb_kriteria WHERE kode_kriteria='$kode_kriteria'"))
        print_msg("Kode sudah ada!");
    else {
        $db->query("INSERT INTO tb_kriteria (kode_kriteria, nama_kriteria) VALUES ('$kode_kriteria', '$nama_kriteria')");
        $db->query("INSERT INTO tb_rel_kriteria(ID1, ID2, nilai) SELECT '$kode_kriteria', kode_kriteria, 1 FROM tb_kriteria");
        $db->query("INSERT INTO tb_rel_kriteria(ID1, ID2, nilai) SELECT kode_kriteria, '$kode_kriteria', 1 FROM tb_kriteria WHERE kode_kriteria<>'$kode_kriteria'");
        redirect_js("index.php?m=kriteria");
    }
} else if ($mod == 'kriteria_ubah') {
    $kode_kriteria = $_POST['kode_kriteria'];
    $nama_kriteria = $_POST['nama_kriteria'];

    if ($kode_kriteria == '' || $nama_kriteria == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    else {
        $db->query("UPDATE tb_kriteria SET kode_kriteria='$kode_kriteria', nama_kriteria='$nama_kriteria' WHERE kode_kriteria='$_GET[ID]'");
        redirect_js("index.php?m=kriteria");
    }
} else if ($act == 'kriteria_hapus') {
    $db->query("DELETE FROM tb_rel_kriteria WHERE ID1='$_GET[ID]'");
    $db->query("DELETE FROM tb_rel_kriteria WHERE ID2='$_GET[ID]'");
    $db->query("DELETE FROM tb_kriteria WHERE kode_kriteria='$_GET[ID]'");
    header("location:index.php?m=kriteria");
}

/** RELASI ALTERNATIF */
else if ($act == 'rel_alternatif_ubah') {
    foreach ($_POST['nilai'] as $key => $val) {
        $db->query("UPDATE tb_penilaian SET nilai='$val' WHERE ID='$key'");
    }
    set_msg('Data berhasil diubah');
    header("location:index.php?m=rel_alternatif");
}

/** relasi kriteria */
else if ($mod == 'rel_kriteria') {
    $ID1 = $_POST['ID1'];
    $ID2 = $_POST['ID2'];
    $nilai = abs($_POST['nilai']);

    if ($ID1 == $ID2 && $nilai <> 1)
        print_msg("Kriteria yang sama harus bernilai 1.");
    else {
        $db->query("UPDATE tb_rel_kriteria SET nilai=$nilai WHERE ID1='$ID1' AND ID2='$ID2'");
        $db->query("UPDATE tb_rel_kriteria SET nilai=1/$nilai WHERE ID2='$ID1' AND ID1='$ID2'");
        print_msg("Nilai kriteria berhasil diubah.", 'success');
    }
} elseif ($mod == 'laporan_tambah') {
    $periode = $_POST['periode'];
    $catatan = $_POST['catatan'];

    if ($periode == '' || $catatan == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    elseif ($db->get_results("SELECT * FROM tb_laporan WHERE periode='$periode'"))
        print_msg("Periode sudah ada!");
    else {
        $last_periode = $db->get_var("SELECT periode FROM tb_laporan ORDER BY periode DESC LIMIT 1");
        if ($last_periode) {
            $hitung = hitungFahpTOPSIS($last_periode);
            dd($last_periode);
            foreach ($hitung['laporan'] as $key => $val) {
                $umur = !empty($val['umur']) ? floatval($val['umur']) : 'NULL';
                $berat = !empty($val['berat']) ? floatval($val['berat']) : 'NULL';
                $tinggi = !empty($val['tinggi']) ? floatval($val['tinggi']) : 'NULL';
                $db->query("INSERT INTO tb_laporan (periode, catatan, `rank`, kode_alternatif, hasil, total, umur, berat, tinggi) VALUES ('$periode', '$catatan', '$val[rank]', '$val[kode_alternatif]', '$val[hasil]', '$val[total]', $umur, $berat, $tinggi)");
                $id_laporan = $db->insert_id;
                foreach ($hitung['tb_penilaian'][$key] as $kriteria => $v)
                    $db->query("INSERT INTO tb_penilaian(id_laporan, kode_kriteria, kategori, bobot) VALUES ('$id_laporan', '$kriteria', '{$v['kategori']}', '{$v['bobot']}')");
            }
        } else {
            $db->query("INSERT INTO tb_laporan (periode, catatan, kode_alternatif) SELECT '$periode', '$catatan', kode_alternatif FROM tb_alternatif");
            $db->query("INSERT INTO tb_penilaian (id_laporan, kode_kriteria) SELECT id_laporan, kode_kriteria FROM tb_laporan, tb_kriteria WHERE periode='$periode'");
        }
        redirect_js("index.php?m=laporan&periode=$periode");
    }
} elseif ($mod == 'laporan_hapus') {
    $periode = $_GET['periode'];
    $db->query("DELETE FROM tb_penilaian WHERE id_laporan IN (SELECT id_laporan FROM tb_laporan WHERE periode='$periode')");
    $db->query("DELETE FROM tb_laporan WHERE periode='$periode'");
    set_msg('Laporan periode berhasil dihapus.');
    redirect_js("index.php?m=laporan");
} elseif ($mod == 'rel_alternatif_ubah') {
    $umur = $_POST['umur'];
    $berat = $_POST['berat'];
    $tinggi = $_POST['tinggi'];

    if ($umur == ''  || $berat == '' || $tinggi == '')
        print_msg("Field bertanda * tidak boleh kosong!");
    else {
        $db->query("UPDATE tb_laporan SET umur='$umur', berat='$berat', tinggi='$tinggi' WHERE id_laporan='$_GET[ID]'");

        $periode = $_GET['periode'];
        $hitung = hitungFahpTOPSIS($periode);

        foreach ($hitung['tb_penilaian'][$_GET['kode_alternatif']] as $key => $val) {
            $db->query("UPDATE tb_penilaian SET kategori='{$val['kategori']}', bobot='{$val['bobot']}' WHERE id_laporan='$_GET[ID]' AND kode_kriteria='$key'");
        }

        set_msg('Data berhasil diubah');
        redirect_js("index.php?m=rel_alternatif&periode=$periode");
    }
}
