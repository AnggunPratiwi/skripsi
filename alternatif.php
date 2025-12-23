<div class="page-header">
    <h1>Alternatif</h1>
</div>
<?= show_msg() ?>
<div class="card mb-3">
    <div class="card-header">
        <form class="form-inline">
            <input type="hidden" name="m" value="alternatif" />
            <div class="mr-1">
                <input class="form-control" type="text" placeholder="Pencarian. . ." name="q" value="<?= _get('q') ?>" />
            </div>
            <div class="mr-1">
                <button class="btn btn-success"><span class="fa fa-sync"></span> Refresh</button>
            </div>
            <div class="mr-1">
                <a class="btn btn-primary" href="?m=alternatif_tambah"><span class="fa fa-plus"></span> Tambah</a>
            </div>
            <div class="mr-1">
                <a class="btn btn-info" href="?m=alternatif_import"><span class="fa fa-file-excel"></span> Import</a>
            </div>
            <div class="mr-1">
                <a class="btn btn-secondary" href="cetak.php?m=alternatif&q=<?= _get('q') ?>" target="_blank"><span class="fa fa-print"></span> Cetak</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-sm m-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Tanggal Lahir</th>
                    <th>Nama Ortu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <?php
            $q = esc_field(_get('q'));
            $rows = $db->get_results("SELECT * FROM tb_alternatif WHERE kode_alternatif LIKE '%$q%' OR nama_balita LIKE '%$q%' ORDER BY kode_alternatif");
            $no = 0;
            foreach ($rows as $row) : ?>
                <tr>
                    <td><?= ++$no ?></td>
                    <td><?= $row->kode_alternatif ?></td>
                    <td><?= $row->nama_balita ?></td>
                    <td><?= $row->jenis_kelamin ?></td>
                    <td><?= $row->tanggal_lahir ?></td>
                    <td><?= $row->nama_ortu ?></td>
                    <td class="text-nowrap">
                        <a class="btn btn-sm btn-warning" href="?m=alternatif_ubah&ID=<?= $row->kode_alternatif ?>"><span class="fa fa-edit"></span></a>
                        <a class="btn btn-sm btn-danger" href="aksi.php?act=alternatif_hapus&ID=<?= $row->kode_alternatif ?>" onclick="return confirm('Hapus data?')"><span class="fa fa-trash"></span></a>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>