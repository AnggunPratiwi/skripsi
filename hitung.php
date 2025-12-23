<div class="page-header">
    <h1>Perhitungan FAHP-TOPSIS</h1>
</div>
<?php
$rel_kriteria = get_rel_kriteria();
foreach ($KRITERIA as $key => $val)
    $atribut[$key] = 'benefit';

$rel_alternatif = get_rel_alternatif();
$fahp = new FAHPTOPSIS($rel_kriteria, $rel_alternatif, $atribut);
$ahp = new AHP($fahp->konversi_kriteria);
// dd($fahp->topsis);
?>
<div class="card mb-3">
    <div class="card-header">
        <a href="#fahp_kriteria" data-toggle="collapse">FAHP KRITERIA (Klik untuk lihat detail)</a>
    </div>
    <div class="card-body collapse show1" id="fahp_kriteria">
        <?php include 'hitung_kriteria.php'; ?>
    </div>
</div>
<div class="card mb-3">
    <div class="card-header">
        <a href="#topsis" data-toggle="collapse">TOPSIS</a>
    </div>
    <div class="card-body collapse show1" id="topsis">
        <?php include 'hitung_topsis.php' ?>
    </div>
</div>
<div class="card mb-3">
    <div class="card-header">
        <strong>Perangkingan FAHP TOPSIS</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover m-0">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Positif</th>
                    <th>Negatif</th>
                    <th>Pref</th>
                    <th>Stunting</th>
                </tr>
            </thead>
            <?php
            $_SESSION['laporan'] = [];
            foreach ($fahp->rank as $key => $val) :
                $_SESSION['laporan'][$key] = [
                    'rank' => $val,
                    'kode_alternatif' => $key,
                    'nama_balita' => $ALTERNATIF[$key]->nama_balita,
                    'berat' => $ALTERNATIF[$key]->berat,
                    'tinggi' => $ALTERNATIF[$key]->tinggi,
                    'umur' => $ALTERNATIF[$key]->umur,
                    'jenis_kelamin' => $ALTERNATIF[$key]->jenis_kelamin,
                    'total' => $fahp->total[$key],
                    'hasil' => $fahp->hasil[$key],
                ];                
            ?>
                <tr>
                    <td><?= $val ?></td>
                    <td><?= $key ?></td>
                    <td><?= $ALTERNATIF[$key]->nama_balita ?></td>
                    <td><?= round($fahp->topsis->jarak[$key]['positif'], 4) ?></td>
                    <td><?= round($fahp->topsis->jarak[$key]['negatif'], 4) ?></td>
                    <td><?= round($fahp->total[$key], 4) ?></td>
                    <td><?= $fahp->hasil[$key] ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
    <div class="card-footer">
        <a class="btn btn-primary" href="?m=laporan_tambah"><span class="fa fa-save"></span> Simpan Penilaian</a>
        <a class="btn btn-secondary" target="_blank" href="cetak.php?m=hitung"><span class="fa fa-print"></span> Cetak</a>
    </div>
</div>