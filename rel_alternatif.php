<div class="page-header">
    <h1>Nilai Bobot Alternatif (z-Score)</h1>
</div>
<?php
show_msg();
$periode = set_value('periode');
$hitung = hitungFahpTOPSIS(_get('periode'));
?>
<div class="card mb-3">
    <div class="card-header">
        <form class="form-inline">
            <input type="hidden" name="m" value="rel_alternatif" />
            <div class="mr-1">
                <select class="form-control" name="periode" onchange="this.form.submit()">
                    <option value="">Pilih Periode</option>
                    <?= get_periode_option($periode) ?>
                </select>
            </div>
            <div class="mr-1">
                <input class="form-control" type="text" name="q" value="<?= _get('q') ?>" placeholder="Pencarian..." />
            </div>
            <div class="mr-1">
                <a class="btn btn-info" href="?m=rel_alternatif_import"><span class="fa fa-file-excel"></span> Import</a>
            </div>
            <div class="mr-1">
                <button class="btn btn-success"><span class="fa fa-sync"></span> Refresh</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-sm m-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama </th>
                    <th>Umur </th>
                    <th>Berat</th>
                    <th>Tinggi</th>
                    <?php foreach ($KRITERIA as $key => $val) : ?>
                        <th><?= $val->nama_kriteria ?></th>
                    <?php endforeach ?>
                    <th>Aksi</th>
                </tr>
            </thead>
            <?php
            $q = esc_field(_get('q'));
            $rows = $db->get_results("SELECT * FROM tb_laporan l INNER JOIN tb_alternatif a ON a.kode_alternatif=l.kode_alternatif WHERE( l.kode_alternatif LIKE '%$q%' OR `nama_balita` LIKE '%$q%' ) AND periode='$periode' ORDER BY l.kode_alternatif");

            foreach ($rows as $row) :
            ?>
                <tr class="nw">
                    <td><?= $row->kode_alternatif ?></td>
                    <td class="text-nowrap"><?= $row->nama_balita; ?></td>
                    <td><?= $row->umur ?></td>
                    <td><?= $row->berat ?></td>
                    <td><?= $row->tinggi ?></td>
                    <?php foreach ($hitung['tb_penilaian'][$row->kode_alternatif] as $k => $v) :

                    ?>
                        <td><?= $v['kategori'] ?> (<?= $v['bobot'] ?>)</td>
                    <?php endforeach ?>
                    <td class="text-nowrap">
                        <a class="btn btn-sm btn-warning" href="?m=rel_alternatif_ubah&ID=<?= $row->id_laporan ?>&periode=<?= $row->periode ?>&kode_alternatif=<?= $row->kode_alternatif ?>"><span class="fa fa-edit"></span></a>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>