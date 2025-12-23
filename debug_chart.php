<?php
include 'functions.php';

// Debug: Check data for a specific toddler
$query = "SELECT l.kode_alternatif, a.nama_balita, l.periode, l.total, l.hasil FROM tb_laporan l JOIN tb_alternatif a ON a.kode_alternatif = l.kode_alternatif WHERE a.nama_balita LIKE '%ADNAN%' ORDER BY l.periode";
$rows = $db->get_results($query);

header('Content-Type: application/json');
echo json_encode($rows, JSON_PRETTY_PRINT);
