<h1>Alternatif</h1>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Jenis Kelamin</th>
        </tr>
    </thead>
    <?php
    $rows = $db->get_results("SELECT * FROM tb_alternatif  ORDER BY kode_alternatif");
    $no = 0;
    foreach ($rows as $row) : ?>
        <tr>
            <td><?= ++$no ?></td>
            <td><?= $row->kode_alternatif ?></td>
            <td><?= $row->nama_balita ?></td>
            <td><?= $row->jenis_kelamin ?></td>
        </tr>
    <?php endforeach ?>
</table>