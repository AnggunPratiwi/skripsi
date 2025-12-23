<div class="card mb-3">
    <div class="card-header">
        <strong>Matriks AHP Kriteria</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover m-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <?php foreach ($KRITERIA as $key => $val) : ?>
                        <th><?= $key ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <?php foreach ($fahp->konversi_kriteria as $key => $val) : ?>
                <tr>
                    <td><?= $key ?></td>
                    <?php foreach ($val as $k => $v) : ?>
                        <td><?= round($v, 3) ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
            <tfoot>
                <tr>
                    <td>Total</td>
                    <?php foreach ($ahp->baris_total as $key => $val) : ?>
                        <td><?= round($val, 3) ?></td>
                    <?php endforeach ?>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="card-body">
        Normalisasi Matriks AHP
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover m-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <?php foreach ($KRITERIA as $key => $val) : ?>
                        <th><?= $key ?></th>
                    <?php endforeach ?>
                    <th>Eigen Vector</th>
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
        <div class="card-footer">
            CI = <?= round($ahp->CI, 4) ?><br />
            RI = <?= round($ahp->RI, 4) ?><br />
            CR = <?= round($ahp->CR, 4) ?> (<?= $ahp->konsistensi ?>)<br />
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <strong>Konversi FAHP</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover m-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <?php foreach ($KRITERIA as $key => $val) : ?>
                        <th colspan="3"><?= $key ?></th>
                    <?php endforeach ?>
                    <th colspan="3">Total</th>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <?php foreach ($KRITERIA as $key => $val) : ?>
                        <th>L</th>
                        <th>M</th>
                        <th>U</th>
                    <?php endforeach ?>
                    <th>L</th>
                    <th>M</th>
                    <th>U</th>
                </tr>
            </thead>
            <?php foreach ($fahp->fahp->mfahp as $key => $val) : ?>
                <tr>
                    <td><?= $key ?></td>
                    <?php foreach ($val as $k => $v) : ?>
                        <?php foreach ($v as $a => $b) : ?>
                            <td><?= round($b, 3) ?></td>
                        <?php endforeach ?>
                    <?php endforeach ?>
                    <?php foreach ($fahp->fahp->lmu[$key] as $a => $b) : ?>
                        <td><?= round($b, 3) ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
            <tr>
                <td colspan="<?= count($KRITERIA) * 3 + 1 ?>">Total</td>
                <?php foreach ($fahp->fahp->lmu_total as $a => $b) : ?>
                    <td><?= round($b, 3) ?></td>
                <?php endforeach ?>
            </tr>
        </table>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <strong>Nilai Sintesis</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover m-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th colspan="3">Nilai Sintesis</th>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <th>L</th>
                    <th>M</th>
                    <th>U</th>
                </tr>
            </thead>
            <?php foreach ($fahp->fahp->si as $key => $val) : ?>
                <tr>
                    <td><?= $key ?></td>
                    <?php foreach ($val as $a => $b) : ?>
                        <td><?= round($b, 3) ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <strong>Penentuan Nilai Vektor (V) dan Nilai Ordinat Defuzzifikasi (d')</strong>
    </div>
    <div class="card-body">
        <?php

        foreach ($fahp->fahp->defuzzifikasi as $key => $val) : ?>
            <div class="card mb-3">
                <div class="card-header"><?= $KRITERIA[$key]->nama_kriteria ?></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover m-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>a = l-u<?= $key ?></th>
                                <th>b = m<?= $key ?>-u<?= $key ?></th>
                                <th>c = m-l</th>
                                <th>d = b-c</th>
                                <th>e = a/d</th>
                                <th>d'</th>
                            </tr>
                        </thead>
                        <?php foreach ($val as $k => $v) : ?>
                            <tr>
                                <td><?= $k ?></td>
                                <?php foreach ($v as $a => $b) : ?>
                                    <td><?= round($b, 3) ?></td>
                                <?php endforeach ?>
                            </tr>
                        <?php endforeach ?>
                        <tfoot>
                            <tr>
                                <td colspan="6">Nilai Minimum (W)</td>
                                <td><?= round($fahp->fahp->w[$key], 3) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <strong>Nilai Vektor W</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover m-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>W</th>
                    <th>W Lokal</th>
                </tr>
            </thead>
            <?php foreach ($fahp->fahp->w as $key => $val) : ?>
                <tr>
                    <td><?= $key ?></td>
                    <td><?= round($val, 3) ?></td>
                    <td><?= round($fahp->fahp->w_lokal[$key], 3) ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</div>