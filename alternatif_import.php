[28/12/25 21.14.35] Kak Gusti Ilkom: </div>
<div class="row">
    <div class="col-md-6">
        <?php
        if (!empty($_FILES)) {

            if (empty($_FILES['excel']['tmp_name'])) {
                print_msg('Pilih file berekstensi *.xls / *.xlsx', 'danger');

            } else {

                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['excel']['tmp_name']);
                $worksheet   = $spreadsheet->getActiveSheet();

                // ambil semua baris
                $arr = [];
                foreach ($worksheet->getRowIterator() as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);

                    foreach ($cellIterator as $cell) {
                        $value = $cell->getValue();

                        if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($cell)) {
                            $value = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
                        }

                        $arr[$row->getRowIndex()][] = $value;
                    }
                }

                if (empty($arr[1])) {
                    print_msg("Header tidak ditemukan di baris pertama.", 'danger');
                } else {

                    $headers = $arr[1];

                    // ubah jadi array asosiatif per baris
                    $arr2 = [];
                    foreach ($arr as $i => $row) {
                        if ($i <= 1) continue; // skip header

                        $rowAssoc = [];
                        foreach ($row as $k => $v) {
                            if (!isset($headers[$k])) continue;
                            $col = trim((string)$headers[$k]);
                            if ($col === '') continue;
                            $rowAssoc[$col] = $v;
                        }

                        // skip baris kosong
                        if (count(array_filter($rowAssoc, fn($x) => $x !== null && $x !== '')) === 0) continue;

                        $arr2[] = $rowAssoc;
                    }

                    // refresh master alternatif dari DB (jangan pakai cache $ALTERNATIF lama)
                    $existCodes = [];
                    $rowsExist = $db->get_results("SELECT kode_alternatif FROM tb_alternatif");
                    foreach ($rowsExist as $r) {
                        $existCodes[$r->kode_alternatif] = true;
                    }

                    $tb_alternatif_new = [];
                    $tb_alternatif_update = [];

                    foreach ($arr2 as $val) {
                        $kode = isset($val['kode_alternatif']) ? trim((string)$val['kode_alternatif']) : '';

                        // jika kode kosong, skip (atau bisa auto-generate kalau Anda mau)
                        if ($kode === '') continue;

                        $item = [
                            'kode_alternatif' => $kode,
                            'jenis_kelamin'   => $val['jenis_kelamin'] ?? '',
                            'tanggal_lahir'   => $val['tanggal_lahir'] ?? '',
                            'nama_ortu'       => $val['nama_ortu'] ?? '',
                            'nama_balita'     => $val['nama_balita'] ?? '',
                        ];

                        if (isset($existCodes[$kode])) {
                            $tb_alternatif_update[] = $item;
                        } else {
                            $tb_alternatif_new[] = $item;
                        }
                    }

                    // INSERT hanya jika ada data baru (ini yang mencegah error array_keys(false))
                    if (!empty($tb_alternatif_new)) {
                        $db->multi_query("tb_alternatif", $tb_alternatif_new);
                    }

                    // UPDATE hanya jika ada data update
                    if (!empty($tb_alternatif_update)) {
                        foreach ($tb_alternatif_update as $item) {
                            $kode = esc_field($item['kode_alternatif']);

                            $nama = esc_field($item['nama_balita']);
                            $jk   = esc_field($item['jenis_kelamin']);
                            $tgl  = esc_field($item['tanggal_lahir']);
                            $ortu = esc_field($item['nama_ortu']);

                            $db->query("UPDATE tb_alternatif SET 
                                nama_balita='$nama', 
                                jenis_kelamin='$jk', 
                                tanggal_lahir='$tgl', 
                                nama_ortu='$ortu'
                                WHERE kode_alternatif='$kode'");
                        }
                    }

                    $ins = count($tb_alternatif_new);
                    $upd = count($tb_alternatif_update);

                    if ($ins === 0 && $upd === 0) {
                        print_msg("Tidak ada data yang diproses (file kosong / format tidak sesuai).", 'warning');
                    } else {
                        print_msg("Import berhasil! Insert: $ins, Update: $upd (data lama tidak dihapus).", 'success');
                    }
                }
            }
        }
        ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Pilih file *.xls / *.xlsx</label>
                <input class="form-control" type="file" name="excel" />
            </div>
            <div class="form-group">
                <button class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                <a class="btn btn-danger" href="?m=alternatif"><span class="fa fa-arrow-left"></span> Kembali</a>
            </div>
        </form>
    </div>
</div>
[28/12/25 21.15.06] Anggun: Oke wait