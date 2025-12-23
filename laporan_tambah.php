<div class="page-header">
    <h1>Tambah Laporan</h1>
</div>
<div class="row">
    <div class="col-sm-6">
        <?php if ($_POST) include 'aksi.php' ?>
        <form method="POST">
            <div class="form-group">
                <label>Periode <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="periode" value="<?= set_value('periode', kode_oto('periode', 'tb_laporan', date('Ym-'), 3)) ?>" />
            </div>
            <div class="form-group">
                <label>Catatan <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="catatan" value="<?= set_value('catatan') ?>" />
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                <a class="btn btn-danger" href="?m=laporan"><span class="fa fa-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>