<?php
include 'functions.php';

$kode_alternatif = _get('kode_alternatif');

if ($kode_alternatif) {
    // Fetch reports for the specific toddler, ordered by period
    // Use get_results to iterate
    $rows = $db->get_results("SELECT periode, total, hasil FROM tb_laporan WHERE kode_alternatif = '$kode_alternatif' ORDER BY periode ASC");

    $categories = [];
    $data = [];

    foreach ($rows as $row) {
        $categories[] = $row->periode;

        $color = '#438AFE'; // Default blue
        if (strtolower(trim($row->hasil)) == 'stunting') {
            $color = '#ffc107'; // Warning Yellow
        }

        $data[] = [
            'y' => (float) $row->total,
            'color' => $color,
            'hasil' => $row->hasil // Pass extra data for tooltip if needed
        ];
    }

    echo json_encode([
        'categories' => $categories,
        'data' => $data
    ]);
} else {
    echo json_encode(['error' => 'No toddler selected']);
}
