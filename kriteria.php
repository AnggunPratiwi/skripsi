<div class="page-header">
    <h1>Kriteria</h1>
</div>
<?= show_msg() ?>
<div class="card card-default">
    <div class="card-header">
        <form class="form-inline">
            <input type="hidden" name="m" value="kriteria" />
            <div class="mr-1">
                <input class="form-control" type="text" placeholder="Pencarian. . ." name="q" value="<?= _get('q') ?>" />
            </div>
            <div class="mr-1">
                <button class="btn btn-success"><span class="fa fa-sync"></span> Refresh</button>
            </div>
            <!-- <div class="mr-1">
                <a class="btn btn-primary" href="?m=kriteria_tambah"><span class="fa fa-plus"></span> Tambah</a>
            </div> -->
            <div class="mr-1">
                <a class="btn btn-secondary" target="_blank" href="cetak.php?m=kriteria"><span class="fa fa-print"></span> Cetak</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped m-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Kriteria</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <?php
            $q = esc_field(_get('q'));
            $rows = $db->get_results("SELECT * FROM tb_kriteria WHERE nama_kriteria LIKE '%$q%' ORDER BY kode_kriteria");
            foreach ($rows as $row) : ?>
                <tr>
                    <td><?= $row->kode_kriteria ?></td>
                    <td><?= $row->nama_kriteria ?></td>
                    <td>
                        <a class="btn btn-sm btn-warning" href="?m=kriteria_ubah&ID=<?= $row->kode_kriteria ?>"><span class="fa fa-edit"></span></a>
                        <!-- <a class="btn btn-sm btn-danger" href="aksi.php?act=kriteria_hapus&ID=<?= $row->kode_kriteria ?>" onclick="return confirm('Hapus data?')"><span class="fa fa-trash"></span></a> -->
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>