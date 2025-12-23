<div class="card mb-3">
    <div class="card-header">
        <strong>Nilai Alternatif</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover m-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <?php foreach ($KRITERIA as $key => $val) : ?>
                        <th><?= $key ?> (<?= round($fahp->fahp->w_lokal[$key], 4) ?>)</th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <?php foreach ($fahp->rel_alternatif as $key => $val) : ?>
                <tr>
                    <td><?= $key ?></td>
                    <?php foreach ($val as $k => $v) : ?>
                        <td><?= round($v, 4) ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <strong>Normalisasi</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover m-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <?php foreach ($KRITERIA as $key => $val) : ?>
                        <th><?= $key ?> </th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <?php foreach ($fahp->topsis->normal as $key => $val) : ?>
                <tr>
                    <td><?= $key ?></td>
                    <?php foreach ($val as $k => $v) : ?>
                        <td><?= round($v, 4) ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <strong>Terbobot</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover m-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <?php foreach ($KRITERIA as $key => $val) : ?>
                        <th><?= $key ?> </th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <?php foreach ($fahp->topsis->terbobot as $key => $val) : ?>
                <tr>
                    <td><?= $key ?></td>
                    <?php foreach ($val as $k => $v) : ?>
                        <td><?= round($v, 4) ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>


<div class="card mb-3">
    <div class="card-header">
        <strong>Solusi Ideal</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover m-0">
            <thead>
                <tr>
                    <th>#</th>
                    <?php foreach ($KRITERIA as $key => $val) : ?>
                        <th><?= $val->nama_kriteria ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <?php foreach ($fahp->topsis->solusi as $key => $val) : ?>
                <tr>
                    <td><?= $key ?></td>
                    <?php foreach ($val as $k => $v) : ?>
                        <td><?= round($v, 3) ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>
<?php foreach ($fahp->topsis->solusi_ideal as $key => $val) : ?>
    <div class="card mb-3">
        <div class="card-header">
            Solusi Ideal <?= ucfirst($key) ?>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover m-0">
                <thead>
                    <tr>
                        <th>Alternatif</th>
                        <?php foreach ($KRITERIA as $k => $v) : ?>
                            <th><?= $v->nama_kriteria ?></th>
                        <?php endforeach ?>
                    </tr>
                </thead>
                <?php foreach ($val as $k => $v) : ?>
                    <tr>
                        <th><?= $k ?></th>
                        <?php foreach ($v as $a => $b) : ?>
                            <td><?= round($b, 5) ?></td>
                        <?php endforeach ?>
                    </tr>
                <?php endforeach ?>
            </table>
        </div>
    </div>
<?php endforeach ?>