<?php
$row = $db->get_row("SELECT * FROM tb_alternatif WHERE kode_alternatif='$_SESSION[ID]'");
?>
<div class="page-header">
    <h1>Hasil Penilaian</h1>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="card mb-3">
            <table class="table">
                <tr>
                    <td>Kode </td>
                    <td> : <?= $row->kode_alternatif ?></td>
                </tr>
                <tr>
                    <td>NIP </td>
                    <td> : <?= $row->nip ?></td>
                </tr>
                <tr>
                    <td>Nama </td>
                    <td> : <?= $row->nama_balita ?></td>
                </tr>
                <tr>
                    <td>Golongan </td>
                    <td> : <?= $row->golongan ?></td>
                </tr>
                <tr>
                    <td>Jabatan </td>
                    <td> : <?= $row->jabatan ?></td>
                </tr>
                <tr>
                    <td>Rank </td>
                    <td> : <?= $row->rank ?></td>
                </tr>
                <tr>
                    <td>Total </td>
                    <td> : <?= round($row->total, 4) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>