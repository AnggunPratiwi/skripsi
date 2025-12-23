<h1>Laporan Kriteria</h1>
<table>
	<thead>
		<tr>
			<th>Kode</th>
			<th>Nama Kriteria</th>
		</tr>
	</thead>
	<?php
	$rows = $db->get_results("SELECT * FROM tb_kriteria ORDER BY kode_kriteria");
	foreach ($rows as $row) : ?>
		<tr>
			<td><?= $row->kode_kriteria ?></td>
			<td><?= $row->nama_kriteria ?></td>
		</tr>
	<?php endforeach ?>
</table>