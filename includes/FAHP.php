<?php
class AHP
{
    public $matrix;
    public $baris_total;
    public $normal;
    public $prioritas;
    public $cm;
    public $CI;
    public $RI;
    public $CR;
    public $konsistensi;

    function __construct($matrix)
    {
        $this->matrix = $matrix;
        $this->baris_total();
        $this->normal();
        $this->prioritas();
        $this->cm();
        $this->konsistensi();
    }

    function konsistensi()
    {
        $nRI = array(
            1 => 0,
            2 => 0,
            3 => 0.58,
            4 => 0.9,
            5 => 1.12,
            6 => 1.24,
            7 => 1.32,
            8 => 1.41,
            9 => 1.46,
            10 => 1.49,
            11 => 1.51,
            12 => 1.48,
            13 => 1.56,
            14 => 1.57,
            15 => 1.59
        );
        $cm = $this->cm;
        $this->CI = count($cm) > 1 ? ((array_sum($cm) / count($cm)) - count($cm)) / (count($cm) - 1) : 0;
        $this->RI = count($cm) > 0 ? $nRI[count($cm)] : 0;
        $this->CR = $this->RI == 0 ? 0 : $this->CI / $this->RI;
        $this->konsistensi = $this->CR <= 0.1 ? 'Konsisten' : 'Tidak Konsisten';
    }

    function cm()
    {
        $this->cm = array();
        foreach ($this->matrix as $key => $val) {
            $this->cm[$key] = 0;
            foreach ($val as $k => $v) {
                $this->cm[$key] += $v * $this->prioritas[$k];
            }
            $this->cm[$key] /= $this->prioritas[$key];
        }
    }

    function prioritas()
    {
        $this->prioritas = array();
        foreach ($this->normal as $key => $val) {
            $this->prioritas[$key] = array_sum($val) / count($val);
        }
    }

    function normal()
    {
        $this->normal = array();
        foreach ($this->matrix as $key => $val) {
            foreach ($val as $k => $v) {
                $this->normal[$key][$k] = $v / $this->baris_total[$k];
            }
        }
    }

    function baris_total()
    {
        $this->baris_total = array();
        foreach ($this->matrix as $key => $val) {
            foreach ($val as $k => $v) {
                if (!isset($this->baris_total[$k]))
                    $this->baris_total[$k] = 0;
                $this->baris_total[$k] += $v;
            }
        }
    }
}

function get_rank($array, $type = 'DESC')
{
    $data = $array;
    if ($type == 'ASC')
        asort($data);
    else
        arsort($data);
    $no = 1;
    $new = array();
    foreach ($data as $key => $value) {
        $new[$key] = $no++;
    }
    return $new;
}

class FAHP
{
    public  $data;
    public $mfahp, $lmu, $lmu_total, $si, $defuzzifikasi, $d_aksen, $w, $w_lokal;

    function __construct($data)
    {
        $this->data = $data;
        $this->get_mfahp();
        $this->get_lmu();
        $this->get_lmu_total();
        $this->get_si();
        $this->get_defuzzifikasi();
        $this->get_w();
    }

    function get_w()
    {
        $total = array_sum($this->w);
        foreach ($this->w as $key => $val) {
            $this->w_lokal[$key] = $val / $total;
        }
    }

    function get_defuzzifikasi()
    {
        $si = $this->si;
        $arr = array();
        foreach ($this->data as $key => $val) {
            foreach ($this->data as $k => $v) {
                if ($key != $k) {
                    $x = array();
                    $x['a'] = $si[$k][0]  - $si[$key][2];
                    $x['b'] = $si[$key][1]  - $si[$key][2];
                    $x['c'] = $si[$k][1]  - $si[$k][0];
                    $x['d'] = $x['b'] - $x['c'];
                    $x['e'] = $x['d'] == 0 ? 0 : $x['a'] / $x['d'];
                    $x['d_aksen'] = ($si[$key][1] >= $si[$k][1]) ? 1 : (($si[$k][0] >= $si[$key][2]) ? 0 : $x['e']);
                    $arr[$key][$k] = $x;
                    $this->d_aksen[$key][$k] = $x['d_aksen'];
                }
            }
            $this->w[$key] = min($this->d_aksen[$key]);
        }
        $this->defuzzifikasi = $arr;
    }

    function get_si()
    {
        $arr = array();
        foreach ($this->lmu as $key => $val) {
            $arr[$key][0] = $val[0] / $this->lmu_total[2];
            $arr[$key][1] = $val[1] / $this->lmu_total[1];
            $arr[$key][2] = $val[2] / $this->lmu_total[0];
        }
        $this->si = $arr;
    }

    function get_lmu_total()
    {
        $arr = array();
        foreach ($this->lmu as $key => $val) {
            foreach ($val as $k => $v) {
                if (!isset($arr[$k]))
                    $arr[$k] = 0;
                $arr[$k] += $v;
            }
        }
        $this->lmu_total = $arr;
    }

    function get_lmu()
    {
        $arr = array();
        foreach ($this->mfahp as $key => $val) {
            foreach ($val as $k => $v) {
                foreach ($v as $a => $b) {
                    if (!isset($arr[$key][$a]))
                        $arr[$key][$a] = 0;
                    $arr[$key][$a] += $b;
                }
            }
        }
        $this->lmu = $arr;
    }

    function get_mfahp()
    {
        $arr = array();
        foreach ($this->data as $key => $val) {
            foreach ($val as $k => $v) {
                $arr[$key][$k] = FAHP_get_triangular($v);
            }
        }
        $this->mfahp = $arr;
    }
}

/**
 * mengambil nilai triangular FUZZY AHP
 */
function FAHP_get_triangular($nilai)
{
    $fahp_triangular = array(
        '1' => array(
            'name' => 'Sama penting dengan',
            'tfn' => array(1, 1, 1),
            'rec' => array(1, 1, 1),
        ),
        '2' => array(
            'name' => 'Mendekati sedikit lebih penting dari',
            'tfn' => array(1, 1, 3 / 2),
            'rec' => array(2 / 3, 1, 1),
        ),
        '3' => array(
            'name' => 'Sedikit lebih penting dari',
            'tfn' => array(1, 3 / 2, 2),
            'rec' => array(1 / 2, 2 / 3, 1),
        ),
        '4' => array(
            'name' => 'Mendekati lebih penting dari',
            'tfn' => array(3 / 2, 2, 5 / 2),
            'rec' => array(2 / 5, 1 / 2, 2 / 3),
        ),
        '5' => array(
            'name' => 'Lebih penting dari',
            'tfn' => array(2, 5 / 2, 3),
            'rec' => array(1 / 3, 2 / 5, 1 / 2),
        ),
        '6' => array(
            'name' => 'Mendekati sangat penting dari',
            'tfn' => array(5 / 2, 3, 7 / 2),
            'rec' => array(2 / 7, 1 / 3, 2 / 5),
        ),
        '7' => array(
            'name' => 'Sangat penting dari',
            'tfn' => array(3, 7 / 2, 4),
            'rec' => array(1 / 4, 2 / 7, 1 / 3),
        ),
        '8' => array(
            'name' => 'Mendekati mutlak dari',
            'tfn' => array(7 / 2, 4, 9 / 2),
            'rec' => array(2 / 9, 1 / 4, 2 / 7),
        ),
        '9' => array(
            'name' => 'Mutlak sangat penting dari',
            'tfn' => array(4, 9 / 2, 9 / 2),
            'rec' => array(2 / 9, 2 / 9, 1 / 4),
        ),
    );

    $keys = array_keys($fahp_triangular);
    $arr = array();
    foreach ($keys as $key) {
        $arr[round(1 / $key, 5) . ""] = $key;
    }

    if (array_key_exists($nilai . "", $fahp_triangular)) {
        return $fahp_triangular[$nilai]['tfn'];
    } else {
        return $fahp_triangular[$arr[round($nilai, 5) . ""]]['rec'];
    }
}

class FAHPTOPSIS
{
    public $rel_kriteria, $rel_alternatif, $atribut;
    public $konversi_kriteria, $fahp;
    public $topsis, $total, $rank, $hasil;

    function __construct($rel_kriteria, $rel_alternatif, $atribut)
    {
        $this->rel_kriteria = $rel_kriteria;
        $this->rel_alternatif = $rel_alternatif;
        $this->atribut = $atribut;

        /** FAHP KRITERIA */
        $this->konversi_kriteria = array();
        foreach ($this->rel_kriteria as $key => $val) {
            foreach ($val as $k => $v) {
                if ($v >= 1) {
                    $this->konversi_kriteria[$key][$k] = ceil($v);
                    $this->konversi_kriteria[$k][$key] = 1 / ceil($v);
                }
            }
        }
        $this->fahp = new FAHP($this->konversi_kriteria);
        $this->topsis = new TOPSIS($rel_alternatif, $this->atribut, $this->fahp->w_lokal);
        $this->total = $this->topsis->pref;
        $this->rank = get_rank($this->total);

        foreach ($this->total as $key => $val) {
            if ($val >= 0.1)
                $this->hasil[$key] = 'Normal';
            else if ($val >= 0.1)
                $this->hasil[$key] = 'stunting';
            else
                $this->hasil[$key] = 'stunting ';
        }
    }
}

class TOPSIS
{
    public $data;
    private $bobot;
    private $atribut;
    public $normal;
    public $terbobot;
    public $solusi;
    public $solusi_ideal;
    public $jarak;
    public $pref;
    public $rank;

    function __construct($data, $atribut, $bobot)
    {
        $this->data = $data;
        $this->bobot = $bobot;
        $this->atribut = $atribut;
        $this->normal();
        $this->terbobot();
        $this->solusi();
        $this->solusi_ideal();
        $this->jarak();
        $this->pref();
        $this->rank();
    }
    function rank()
    {
        $pref = $this->pref;
        arsort($pref);
        $arr = array();
        $no = 1;
        foreach ($pref as $key => $val) {
            $arr[$key] = $no++;
        }
        $this->rank = $arr;
    }
    function pref()
    {
        $arr = array();
        foreach ($this->jarak as $key => $val) {
            $arr[$key] = ($val['positif'] + $val['negatif']) == 0 ? 0 : $val['negatif'] / ($val['positif'] + $val['negatif']);
        }
        $this->pref = $arr;
    }
    function jarak()
    {
        $arr = array();
        foreach ($this->solusi_ideal as $key => $val) {
            foreach ($val as $k => $v) {
                $arr[$k][$key] = sqrt(array_sum($v));
            }
        }
        $this->jarak = $arr;
    }
    function solusi_ideal()
    {
        $arr = array();
        foreach ($this->solusi as $key => $val) {
            foreach ($this->terbobot as $k => $v) {
                foreach ($v as $a => $b) {
                    $arr[$key][$k][$a] = pow($b - $val[$a], 2);
                }
            }
        }
        $this->solusi_ideal = $arr;
    }
    function solusi()
    {
        $arr = array();
        foreach ($this->terbobot as $key => $val) {
            foreach ($val as $k => $v) {
                $arr[$k][] = $v;
            }
        }
        $arr2 = array();
        foreach ($arr as $key => $val) {
            $arr2['positif'][$key] = strtolower($this->atribut[$key]) == 'benefit' ? max($val) : min($val);
            $arr2['negatif'][$key] = strtolower($this->atribut[$key]) == 'cost' ? max($val) : min($val);
        }
        $this->solusi = $arr2;
    }
    function terbobot()
    {
        $arr = array();
        foreach ($this->normal as $key => $val) {
            foreach ($val as $k => $v) {
                $arr[$key][$k] = $v * $this->bobot[$k];
            }
        }
        $this->terbobot = $arr;
    }
    function normal()
    {
        $arr = array();
        foreach ($this->data as $key => $val) {
            foreach ($val as $k => $v) {
                if (!isset($arr[$k]))
                    $arr[$k] = 0;
                $arr[$k] += pow($v, 2);
            }
        }
        $arr2 = array();
        foreach ($this->data as $key => $val) {
            foreach ($val as $k => $v) {
                $arr2[$key][$k] = $v / sqrt($arr[$k]);
            }
        }
        $this->normal = $arr2;
    }
}
