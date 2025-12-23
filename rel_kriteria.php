<div class="page-header">
    <h1>Nilai Bobot Kriteria</h1>
</div>
<?php
if ($_POST) include 'aksi.php';
$rel_kriteria = get_rel_kriteria();
$ahp = new AHP($rel_kriteria);
foreach ($ahp->prioritas as $key => $val) {
    $db->query("UPDATE tb_kriteria SET nilai_kriteria='$val' WHERE kode_kriteria='$key'");
}
?>
<div class="card card-default">
    <div class="card-header">
        <form class="form-inline" method="post">
            <div class="mr-1">
                <select class="form-control" name="ID1">
                    <?= get_kriteria_option($_POST['ID1']) ?>
                </select>
            </div>
            <div class="mr-1">
                <select class="form-control" name="nilai">
                    <?= get_nilai_option($_POST['nilai']) ?>
                </select>
            </div>
            <div class="mr-1">
                <select class="form-control" name="ID2">
                    <?= get_kriteria_option($_POST['ID2']) ?>
                </select>
            </div>
            <div class="mr-1">
                <button class="btn btn-primary"><span class="fa fa-edit"></span> Ubah</a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <?php foreach ($rel_kriteria as $key => $val) : ?>
                        <th><?= $key ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <?php foreach ($rel_kriteria as $key => $val) : ?>
                <tr>
                    <td><?= $key ?></td>
                    <td><?= $KRITERIA[$key]->nama_kriteria ?></td>
                    <?php foreach ($val as $k => $v) : ?>
                        <td><?= round($v, 3) ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
            <tfoot>
                <td>&nbsp;</td>
                <td>Total</td>
                <?php foreach ($ahp->baris_total as $k => $v) : ?>
                    <td><?= round($v, 3) ?></td>
                <?php endforeach ?>
            </tfoot>
        </table>
    </div>
    <div class="card-body">
        <h4>Normalisasi</h4>
    </div>
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>Kode</th>
                <?php foreach ($rel_kriteria as $key => $val) : ?>
                    <th><?= $key ?></th>
                <?php endforeach ?>
                <th>Prioritas</th>
                <th>Consistency Measure</th>
            </tr>
        </thead>
        <?php foreach ($ahp->normal as $key => $val) : ?>
            <tr>
                <td><?= $key ?></td>
                <?php foreach ($val as $k => $v) : ?>
                    <td><?= round($v, 3) ?></td>
                <?php endforeach ?>
                <td><?= round($ahp->prioritas[$key], 3) ?></td>
                <td><?= round($ahp->cm[$key], 3) ?></td>
            </tr>
        <?php endforeach ?>
    </table>
    <div class="card-body">
        <?php
        echo "Consistency Index: " . round($ahp->CI, 3) . "<br />";
        echo "Ratio Index: " . round($ahp->RI, 3) . "<br />";
        echo "Consistency Ratio: " . round($ahp->CR, 3) . " (" . $ahp->konsistensi . ")<br />";
        ?>
    </div>
</div>