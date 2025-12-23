<?php
$row = $db->get_row("SELECT * FROM tb_laporan l INNER JOIN tb_alternatif a ON a.kode_alternatif=l.kode_alternatif WHERE id_laporan='$_GET[ID]'");
?>
<div class="page-header">
    <h1>Ubah Penilaian</h1>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php if ($_POST) include 'aksi.php' ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Kode <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="kode_alternatif" readonly="readonly" value="<?= $row->kode_alternatif ?>" />
            </div>
            <div class="form-group">
                <label>Nama <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="nama_balita" value="<?= set_value('nama_balita', $row->nama_balita) ?>" readonly />
            </div>
            <div class="form-group">
                <label>Umur <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="umur" value="<?= set_value('umur', $row->umur) ?>" />
            </div>
            <div class="form-group">
                <label>Berat <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="berat" value="<?= set_value('berat', $row->berat) ?>" />
            </div>
            <div class="form-group">
                <label>Tinggi <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="tinggi" value="<?= set_value('tinggi', $row->tinggi) ?>" />
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                <a class="btn btn-danger" href="?m=alternatif"><span class="fa fa-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>