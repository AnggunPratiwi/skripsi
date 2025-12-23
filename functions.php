<?php
session_start();

include 'config.php';
include 'includes/db.php';
$db = new DB($config['server'], $config['username'], $config['password'], $config['database_name']);
include 'includes/SimpleImage.php';
include 'includes/FAHP.php';
include 'includes/ZScoreWHO.php';
require 'composer/vendor/autoload.php';


function _post($key, $val = null)
{
    global $_POST;
    if (isset($_POST[$key]))
        return $_POST[$key];
    else
        return $val;
}

function _get($key, $val = null)
{
    global $_GET;
    if (isset($_GET[$key]))
        return $_GET[$key];
    else
        return $val;
}

function _session($key, $val = null)
{
    global $_SESSION;
    if (isset($_SESSION[$key]))
        return $_SESSION[$key];
    else
        return $val;
}

$mod = _get('m');
$act = _get('act');

$rows = $db->get_results("SELECT * FROM tb_alternatif ORDER BY kode_alternatif");
foreach ($rows as $row) {
    $ALTERNATIF[$row->kode_alternatif] = $row;
}

$rows = $db->get_results("SELECT * FROM tb_kriteria ORDER BY kode_kriteria");
foreach ($rows as $row) {
    $KRITERIA[$row->kode_kriteria] = $row;
    $target = $row->kode_kriteria;
}

/** ============================== */
DEFINE('ABSPATH', dirname(__FILE__) . '/');
function is_able($mod)
{
    $role = array(
        'admin' => array(
            'kriteria',
            'rel_kriteria',
            'alternatif',
            'rel_alternatif',
            'hitung',
            'laporan',
        ),
        'penilai' => array(
            // 'kriteria',
            // 'rel_kriteria',
            // 'alternatif',
            'rel_alternatif',
            'hitung',
            'laporan',
        ),
        'alternatif' => array(
            'hasil',
        ),
        'guest' => array(),
    );
    if (!_session('level'))
        $_SESSION['level'] = 'guest';
    if (!isset($role[_session('level')]))
        $_SESSION['level'] = 'guest';
    $level = strtolower(_session('level'));
    return in_array($mod, (array)$role[$level]);
}

function is_alternatif()
{
    return _session('level') == 'alternatif';
}

function is_hidden($mod)
{
    return (is_able($mod)) ? '' : 'hidden';
}

function get_image_url($filename, $pref_f = "", $pref_d = "")
{
    $location = "assets/img/{$pref_f}{$filename}";

    $file = ABSPATH . $location;
    if (is_file($file))
        return $pref_d . $location;
    else
        return $pref_d . "assets/img/no_image.png";
}

function get_relasi()
{
    global $db;
    $data = array();
    $rows = $db->get_results("SELECT * 
        FROM tb_penilaian r INNER JOIN tb_kriteria k ON k.kode_kriteria=r.kode_kriteria
        WHERE k.dicari=0
        ORDER BY kode_alternatif, r.kode_kriteria");
    foreach ($rows as $row) {
        $data[$row->kode_alternatif][$row->kode_kriteria] = $row->nilai;
    }
    return $data;
}

function get_kriteria_option($selected)
{
    global $KRITERIA;
    $a = '';
    foreach ($KRITERIA as $key => $val) {
        if ($key == $selected)
            $a .= "<option value='$key' selected>$val->nama_kriteria</option>";
        else
            $a .= "<option value='$key'>$val->nama_kriteria</option>";
    }
    return $a;
}

function get_alternatif_option($selected)
{
    global $ALTERNATIF;
    $a = '';
    foreach ($ALTERNATIF as $key => $val) {
        if ($key == $selected)
            $a .= "<option value='$key' selected>$val->nama_balita</option>";
        else
            $a .= "<option value='$key'>$val->nama_balita</option>";
    }
    return $a;
}
$POIN = [
    'Rendah' => 0.25,
    'Sedang' => 15,
    'Berat' => 50,
];
function get_poin_option($selected)
{
    global $POIN;
    $a = '';
    foreach ($POIN as $key => $val) {
        if ($key == $selected)
            $a .= "<option value='$key' selected>$key</option>";
        else
            $a .= "<option value='$key'>$key</option>";
    }
    return $a;
}

function get_jenis_kelamin_option($selected)
{
    $jenis_kelamin = ['Laki-Laki' => 'Laki-Laki', 'Perempuan' => 'Perempuan'];
    $a = '';
    foreach ($jenis_kelamin as $key => $val) {
        if ($key == $selected)
            $a .= "<option value='$key' selected>$key</option>";
        else
            $a .= "<option value='$key'>$key</option>";
    }
    return $a;
}

function get_dicari()
{
    global $KRITERIA;
    end($KRITERIA);
    $dicari = key($KRITERIA);
    reset($KRITERIA);
    return $dicari;
}

function set_value($key = null, $default = null)
{
    global $_POST;
    if (isset($_POST[$key]))
        return $_POST[$key];

    if (isset($_GET[$key]))
        return $_GET[$key];

    return $default;
}

function kode_oto($field, $table, $prefix, $length)
{
    global $db;
    $var = (string)$db->get_var("SELECT $field FROM $table WHERE $field REGEXP '{$prefix}[0-9]{{$length}}' ORDER BY $field DESC");
    if ($var) {
        return $prefix . substr(str_repeat('0', $length) . (substr($var, -$length) + 1), -$length);
    } else {
        return $prefix . str_repeat('0', $length - 1) . 1;
    }
}

function esc_field($str)
{
    return addslashes((string)$str);
}

function redirect_js($url)
{
    echo '<script type="text/javascript">window.location.replace("' . $url . '");</script>';
}

function alert($url)
{
    echo '<script type="text/javascript">alert("' . $url . '");</script>';
}

function set_msg($msg, $type = 'success')
{
    $_SESSION['message'] = ['msg' => $msg, 'type' => $type];
}

function show_msg()
{
    if (_session('message'))
        print_msg($_SESSION['message']['msg'], $_SESSION['message']['type']);
    unset($_SESSION['message']);
}

function print_msg($msg, $type = 'danger')
{
    echo ('<div class="alert alert-' . $type . ' alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . $msg . '</div>');
}
function parse_file_name($file_name)
{
    $x = strtolower($file_name);
    $x = str_replace(array(' '), '-', $x);
    return $x;
}

function get_atribut_option($selected = '')
{
    $atribut = array('benefit' => 'Benefit', 'cost' => 'Cost');
    $a = '';
    foreach ($atribut as $key => $value) {
        if ($selected == $key)
            $a .= "<option value='$key' selected>$value</option>";
        else
            $a .= "<option value='$key'>$value</option>";
    }
    return $a;
}

function get_rel_alternatif()
{
    global $db;
    $rows = $db->get_results("SELECT * FROM tb_penilaian ORDER BY kode_alternatif, kode_kriteria");
    $data = array();
    foreach ($rows as $row) {
        $data[$row->kode_alternatif][$row->kode_kriteria] = $row->bobot;
    }
    return $data;
}

function dd($arr)
{
    echo '<pre>' . print_r($arr, 1) . '</pre>';
}

function tgl_indo($date)
{
    $tanggal = explode('-', $date);

    $array_bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    $bulan = $array_bulan[$tanggal[1] * 1];

    return $tanggal[2] . ' ' . $bulan . ' ' . $tanggal[0];
}

function get_rel_kriteria()
{
    global $db;
    $rows = $db->get_results("SELECT * FROM tb_rel_kriteria ORDER BY ID1, ID2");
    $arr = array();
    foreach ($rows as $row) {
        $arr[$row->ID1][$row->ID2] = $row->nilai;
    }
    return $arr;
}

function get_nilai_option($selected = '')
{
    $nilai = array(
        '1' => 'Sama penting dengan',
        '2' => 'Mendekati sedikit lebih penting dari',
        '3' => 'Sedikit lebih penting dari',
        '4' => 'Mendekati lebih penting dari',
        '5' => 'Lebih penting dari',
        '6' => 'Mendekati sangat penting dari',
        '7' => 'Sangat penting dari',
        '8' => 'Mendekati mutlak dari',
        '9' => 'Mutlak sangat penting dari',
    );
    $a = '';
    foreach ($nilai as $key => $value) {
        if ($selected == $key)
            $a .= "<option value='$key' selected>$key - $value</option>";
        else
            $a .= "<option value='$key'>$key - $value</option>";
    }
    return $a;
}

function get_periode_option($selected)
{
    global $db;
    $a = '';
    foreach ($db->get_results("SELECT periode FROM tb_laporan GROUP BY periode ORDER BY periode DESC") as $row) {
        if ($row->periode == $selected)
            $a .= "<option value='$row->periode' selected>$row->periode</option>";
        else
            $a .= "<option value='$row->periode'>$row->periode</option>";
    }
    return $a;
}

function hitungFahpTOPSIS($periode)
{
    global $db, $ALTERNATIF, $KRITERIA;
    $zscore = new ZScoreWHO("assets/who_lms_3bulan.csv");
    $rel_alternatif = [];
    $tb_penilaian = [];
    $rows = $db->get_results("SELECT * FROM tb_laporan l INNER JOIN tb_alternatif a ON a.kode_alternatif=l.kode_alternatif WHERE periode='$periode'");
    foreach ($rows as $row) {
        $umur = $row->umur;
        $jenis_kelamin = $row->jenis_kelamin;
        $berat = $row->berat;
        $tinggi = $row->tinggi;
        $imt = $tinggi ? $berat / pow($tinggi / 100, 2) : 0;

        $z_tbu = $zscore->hitungZScore($umur, $jenis_kelamin, "TB/U", $tinggi);
        $c01 = ZScoreWHO::kategoriTBU($z_tbu);

        $z_bbu = $zscore->hitungZScore($umur, $jenis_kelamin, "BB/U", $berat);
        $c02 = ZScoreWHO::kategoriBBU($z_bbu);

        $z_bbtb = $zscore->hitungZScore($tinggi, $jenis_kelamin, "BB/TB", $berat);
        $c03 = ZScoreWHO::kategoriBBTB($z_bbtb);

        $z_imtu = $zscore->hitungZScore($umur, $jenis_kelamin, "IMT/U", $imt);
        $c04 = ZScoreWHO::kategoriIMTU($z_imtu);

        $rel_alternatif[$row->kode_alternatif] = [
            'C01' => $c01['bobot'],
            'C02' => $c02['bobot'],
            'C03' => $c03['bobot'],
            'C04' => $c04['bobot'],
        ];
        $tb_penilaian[$row->kode_alternatif] = [
            'C01' => $c01,
            'C02' => $c02,
            'C03' => $c03,
            'C04' => $c04,
        ];
    }

    $rel_kriteria = get_rel_kriteria();
    foreach ($KRITERIA as $key => $val)
        $atribut[$key] = 'benefit';

    $fahp = new FAHPTOPSIS($rel_kriteria, $rel_alternatif, $atribut);
    $laporan = [];
    foreach ($rows as $row) {
        $laporan[$row->kode_alternatif] = [
            'rank' => $fahp->rank[$row->kode_alternatif],
            'kode_alternatif' => $row->kode_alternatif,
            'nama_balita' => $row->nama_balita,
            'berat' => $row->berat,
            'tinggi' => $row->tinggi,
            'umur' => $row->umur,
            'jenis_kelamin' => $row->jenis_kelamin,
            'total' => $fahp->total[$row->kode_alternatif],
            'hasil' => $fahp->hasil[$row->kode_alternatif],
        ];
        $db->query("UPDATE tb_laporan SET `rank`='{$laporan[$row->kode_alternatif]['rank']}', hasil='{$laporan[$row->kode_alternatif]['hasil']}', total='{$laporan[$row->kode_alternatif]['total']}' WHERE id_laporan='$row->id_laporan'");
    }
    return ['laporan' => $laporan, 'fahp' => $fahp, 'rel_alternatif' => $rel_alternatif, 'tb_penilaian' => $tb_penilaian];
}
