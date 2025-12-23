<?php

include 'functions.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$periode = _get('periode');

$data[] = [
    'Rank',
    'Kode Alternatif',
    'Nama Alternatif',
    'Jenis Kelamin',
    'Umur',
    'Berat',
    'Tinggi',
    'Total',
    'Stunting'
];

$sql = "
    SELECT *
    FROM tb_laporan l
    INNER JOIN tb_alternatif a
        ON a.kode_alternatif = l.kode_alternatif
";

if ($periode != '') {
    $sql .= " WHERE l.periode = '$periode'";
}

$sql .= " ORDER BY l.rank";

$rows = $db->get_results($sql);

foreach ($rows as $row) {
    $data[] = [
        $row->rank,
        $row->kode_alternatif,
        $row->nama_balita,
        $row->jenis_kelamin,
        $row->umur,
        $row->berat,
        $row->tinggi,
        $row->total,
        $row->hasil
    ];
}

// Buat spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Isi data array ke sheet
$rowIndex = 1;
foreach ($data as $row) {
    $colIndex = 'A'; // mulai dari kolom A
    foreach ($row as $cell) {
        $sheet->setCellValue($colIndex . $rowIndex, $cell);
        $colIndex++;
    }
    $rowIndex++;
}

// Simpan sebagai Excel (XLSX)
$writer = new Xlsx($spreadsheet);

// Nama file
$filename = "data.xlsx";

// Untuk download langsung ke browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Output
$writer->save('php://output');
exit;
