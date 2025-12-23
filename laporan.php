<div class="page-header">
    <h1>Laporan Hasil Perhitungan</h1>
</div>
<?php
show_msg();
$periode = _get('periode');
$hitung = hitungFahpTOPSIS($periode);
?>
<div class="card mb-3">
    <div class="card-header">
        <form class="form-inline">
            <input type="hidden" name="m" value="laporan" />
            <div class="mr-1">
                <select class="form-control" name="periode" onchange="this.form.submit()">
                    <option value="">Pilih Periode</option>
                    <?= get_periode_option($periode) ?>
                </select>
            </div>
            <div class="mr-1">
                <input class="form-control" type="text" placeholder="Pencarian. . ." name="q" value="<?= _get('q') ?>" />
            </div>
            <div class="mr-1">
                <button class="btn btn-success"><span class="fa fa-sync"></span> Refresh</button>
            </div>
            <div class="mr-1">
                <a class="btn btn-info" href="excel.php?m=laporan&periode=<?= _get('periode') ?>"><span class="fa fa-file-excel"></span> Export</a>
            </div>
            <div class="mr-1">
                <a class="btn btn-secondary" href="cetak.php?m=laporan&periode=<?= _get('periode') ?>" target="_blank"><span class="fa fa-print"></span> Cetak</a>
            </div>
            <div class="mr-1">
                <a class="btn btn-danger" href="aksi.php?m=laporan_hapus&periode=<?= _get('periode') ?>" onclick="return confirm('Hapus Laporan periode ini?')"><span class="fa fa-trash"></span> Hapus</a>
            </div>
            <div class="mr-1">
                <a class="btn btn-primary" href="?m=laporan_tambah"><span class="fa fa-save"></span> Tambah</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-sm m-0">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Total</th>
                    <th>Hasil</th>
                </tr>
            </thead>
            <?php
            $q = esc_field(_get('q'));

            $sql = "
                SELECT *
                FROM tb_laporan l
                INNER JOIN tb_alternatif a 
                    ON a.kode_alternatif = l.kode_alternatif
                WHERE (l.kode_alternatif LIKE '%$q%' 
                    OR a.nama_balita LIKE '%$q%')
            ";

            if ($periode != '') {
                $sql .= " AND l.periode = '$periode'";
            }

            $sql .= " ORDER BY l.rank";

            $rows = $db->get_results($sql);

            $no = 0;
            foreach ($rows as $row) : ?>
                <tr>
                    <td><?= ++$no ?></td>
                    <td><?= $row->kode_alternatif ?></td>
                    <td><?= $row->nama_balita ?></td>
                    <td><?= round($row->total, 4) ?></td>
                    <td><?= $row->hasil ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>