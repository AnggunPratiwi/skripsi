<?php
class ZScoreWHO
{

    private $lmsData;

    public function __construct($filePath)
    {
        $this->lmsData = $this->loadCSV($filePath);
    }

    private function loadCSV($filePath)
    {
        $data = [];
        if (($handle = fopen($filePath, "r")) !== false) {
            fgetcsv($handle); // skip header
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                list($umur, $jenis_kelamin, $indikator, $L, $M, $S) = $row;
                $umur = intval($umur);
                $data[$jenis_kelamin][$indikator][$umur] = [
                    "L" => floatval($L),
                    "M" => floatval($M),
                    "S" => floatval($S)
                ];
            }
            fclose($handle);
        }
        return $data;
    }

    private function getLMS($umur, $jenis_kelamin, $indikator)
    {
        $dataset = $this->lmsData[$jenis_kelamin][$indikator];
        if (isset($dataset[$umur])) return $dataset[$umur];

        $keys = array_keys($dataset);
        sort($keys);

        $lowerKeys = array_filter($keys, fn($v) => $v <= $umur);
        $upperKeys = array_filter($keys, fn($v) => $v >= $umur);

        if (empty($lowerKeys) || empty($upperKeys)) {
            // Kasus di luar range dataset
            // misal umur < min($keys) atau umur > max($keys)
            $nearest = end($keys); // default ke terakhir
            if ($umur < min($keys)) $nearest = min($keys);
            if ($umur > max($keys)) $nearest = max($keys);
            return $dataset[$nearest];
        }

        $lower = max($lowerKeys);
        $upper = min($upperKeys);

        if ($lower === $upper) return $dataset[$lower];

        $ratio = ($umur - $lower) / ($upper - $lower);
        $L = $dataset[$lower]["L"] + $ratio * ($dataset[$upper]["L"] - $dataset[$lower]["L"]);
        $M = $dataset[$lower]["M"] + $ratio * ($dataset[$upper]["M"] - $dataset[$lower]["M"]);
        $S = $dataset[$lower]["S"] + $ratio * ($dataset[$upper]["S"] - $dataset[$lower]["S"]);
        return ["L" => $L, "M" => $M, "S" => $S];
    }

    public function hitungZScore($umur, $jenis_kelamin, $indikator, $X)
    {
        $params = $this->getLMS($umur, $jenis_kelamin, $indikator);
        $L = $params["L"];
        $M = $params["M"];
        $S = $params["S"];
        if ($L == 0) {
            $z = log($X / $M) / $S;
        } else {
            $z = (pow(($X / $M), $L) - 1) / ($L * $S);
        }
        return round($z, 2);
    }

    // --- Kategori + Bobot ---
    public static function kategoriTBU($z)
    {
        if ($z > 2) return ["kategori" => "Tinggi", "bobot" => 4];
        elseif ($z >= -2) return ["kategori" => "Normal", "bobot" => 3];
        elseif ($z >= -3) return ["kategori" => "Pendek", "bobot" => 2];
        else return ["kategori" => "Sangat Pendek", "bobot" => 1];
    }

    public static function kategoriBBU($z)
    {
        if ($z > 2) return ["kategori" => "Gemuk", "bobot" => 4];
        elseif ($z >= -2) return ["kategori" => "Normal", "bobot" => 3];
        elseif ($z >= -3) return ["kategori" => "Kurus", "bobot" => 2];
        else return ["kategori" => "Sangat Kurus", "bobot" => 1];
    }

    public static function kategoriBBTB($z)
    {
        if ($z > 2) return ["kategori" => "Gizi Lebih", "bobot" => 4];
        elseif ($z >= -2) return ["kategori" => "Normal", "bobot" => 3];
        elseif ($z >= -3) return ["kategori" => "Gizi Kurang", "bobot" => 2];
        else return ["kategori" => "Gizi Buruk", "bobot" => 1];
    }

    public static function kategoriIMTU($z)
    {
        if ($z > 2) return ["kategori" => "Gizi Lebih", "bobot" => 4];
        elseif ($z > 1) return ["kategori" => "Risiko Gizi Lebih", "bobot" => 3];
        elseif ($z >= -2) return ["kategori" => "Normal", "bobot" => 3];
        elseif ($z >= -3) return ["kategori" => "Gizi Kurang", "bobot" => 2];
        else return ["kategori" => "Gizi Buruk", "bobot" => 1];
    }
}
