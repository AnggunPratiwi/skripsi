<?php
$row = $db->get_row("SELECT * FROM tb_alternatif WHERE kode_alternatif='$_GET[ID]'");
?>
<div class="page-header">
    <h1>Ubah Alternatif</h1>
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
                <input class="form-control" type="text" name="nama_balita" value="<?= set_value('nama_balita', $row->nama_balita) ?>" />
            </div>
            <div class="form-group">
                <label>Jenis Kelamin <span class="text-danger">*</span></label>
                <select class="form-control" name="jenis_kelamin">
                    <?= get_jenis_kelamin_option(set_value('jenis_kelamin', $row->jenis_kelamin)) ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Lahir <span class="text-danger">*</span></label>
                <input class="form-control" type="date" name="tanggal_lahir" value="<?= set_value('tanggal_lahir', $row->tanggal_lahir) ?>" />
            </div>
            <div class="form-group">
                <label>Nama Ortu <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="nama_ortu" value="<?= set_value('nama_ortu', $row->nama_ortu) ?>" />
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                <a class="btn btn-danger" href="?m=alternatif"><span class="fa fa-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>