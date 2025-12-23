<div class="page-header">
    <h1>Import Data Penilaian</h1>
</div>
<?php
show_msg();
$periode = set_value('periode');
?>
<div class="row">
    <div class="col-md-6">
        <?php if ($_FILES) {
            if (!$_FILES['excel']['tmp_name']) {
                print_msg('Pilih file berekstensi *.xls');
            } else {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['excel']['tmp_name']);
                $worksheet = $spreadsheet->getActiveSheet();
                $arr = array();
                foreach ($worksheet->getRowIterator() as $row) {
                    foreach ($row->getCellIterator() as $cell) {
                        $value = $cell->getValue();

                        // Konversi tanggal Excel ke format tanggal normal
                        if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($cell)) {
                            $value = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
                        }
                        $arr[$row->getRowIndex()][]  = $value;
                    }
                }
                $headers = $arr[1];
                $tb_alternatif = array();
                foreach ($arr as $key => $val) {
                    if ($key > 1) {
                        foreach ($val as $k => $v) {
                            $tb_alternatif[$key][$headers[$k]] = $v;
                        }
                    }
                }
                foreach ($tb_alternatif as $key => $val) {
                    $db->query("UPDATE tb_laporan SET umur='{$val['umur']}', berat='{$val['berat']}', tinggi='{$val['tinggi']}' WHERE kode_alternatif='{$val['kode_alternatif']}' AND periode='$periode'");
                }

                print_msg("Import data berhasil!", 'success');
            }
        }
        ?>
        <form method="post" enctype="multipart/form-data">

            <div class="form-group">
                <label>Pilih file *.xls</label>
                <select class="form-control" name="periode">
                    <?= get_periode_option($periode) ?>
                </select>
            </div>
            <div class="form-group">
                <label>Pilih file *.xls</label>
                <input class="form-control" type="file" name="excel" />
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                <a class="btn btn-danger" href="?m=rel_alternatif&periode=<?= $periode ?>"><span class="fa fa-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>