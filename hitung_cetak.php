<h1>Laporan Hasil Perhitungan</h1>

<?php
$rel_kriteria = get_rel_kriteria();
foreach ($KRITERIA as $key => $val)
    $atribut[$key] = 'benefit';

$rel_alternatif = get_rel_alternatif();
$fahp = new FAHPTOPSIS($rel_kriteria, $rel_alternatif, $atribut);
$ahp = new AHP($fahp->konversi_kriteria);
?>
<table class="table table-bordered table-striped table-hover m-0">
    <thead>
        <tr>
            <th>Rank</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Umur</th>
            <th>Jenis Kelamin</th>
            <th>Berat</th>
            <th>Tinggi</th>
            <th>Total</th>
            <th>Stunting</th>
        </tr>
    </thead>
    <?php foreach ($fahp->rank as $key => $val) : ?>
        <tr>
            <td><?= $val ?></td>
            <td><?= $key ?></td>
            <td><?= $ALTERNATIF[$key]->nama_balita ?></td>
            <td><?= $ALTERNATIF[$key]->umur ?></td>
            <td><?= $ALTERNATIF[$key]->jenis_kelamin ?></td>
            <td><?= $ALTERNATIF[$key]->berat ?></td>
            <td><?= $ALTERNATIF[$key]->tinggi ?></td>
            <td><?= round($fahp->total[$key], 4) ?></td>
            <td><?= $fahp->hasil[$key] ?></td>
        </tr>
    <?php endforeach ?>
</table>