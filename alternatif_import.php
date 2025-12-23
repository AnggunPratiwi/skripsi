<div class="page-header">
    <h1>Import Data Alternatif</h1>
</div>
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
                // dd($tb_alternatif);
                $tb_alternatif = array_values($tb_alternatif);

                $db->query("DELETE FROM tb_penilaian");
                $db->query("DELETE FROM tb_laporan");
                $db->query("DELETE FROM tb_alternatif");
                $db->multi_query("tb_alternatif", $tb_alternatif);
                print_msg("Import data berhasil!", 'success');
            }
        }
        ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Pilih file *.xls</label>
                <input class="form-control" type="file" name="excel" />
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                <a class="btn btn-danger" href="?m=alternatif"><span class="fa fa-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>