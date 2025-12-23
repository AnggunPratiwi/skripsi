<h1>Laporan Hasil Perhitungan</h1>
<?php
$periode = _get('periode');
// Validasi periode
if(empty($periode)) {
    echo '<p style="color:red;text-align:center;">Periode tidak ditemukan!</p>';
    exit;
}
?>
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

    // Query yang sama dengan laporan.php
    $sql = "
        SELECT *
        FROM tb_laporan l
        INNER JOIN tb_alternatif a 
            ON a.kode_alternatif = l.kode_alternatif
        WHERE (l.kode_alternatif LIKE '%$q%' 
            OR a.nama_balita LIKE '%$q%')
    ";

    // Tambahkan kondisi periode hanya jika tidak kosong
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