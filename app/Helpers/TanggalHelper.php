<?php

namespace App\Helpers;

class TanggalHelper
{
    public function tgl_indo($tgl)
    {
        $ubah = gmdate($tgl, time() + 60 * 60 * 8);
        $pecah = explode("-", $ubah);
        $tanggal = $pecah[2];
        $bulan = $this->bulan($pecah[1]);
        $tahun = $pecah[0];
        return $tanggal . ' ' . $bulan . ' ' . $tahun;
    }

    public function bulan($bln)
    {
        switch ($bln) {
            case 1:
                return "Januari";
                break;
            case 2:
                return "Februari";
                break;
            case 3:
                return "Maret";
                break;
            case 4:
                return "April";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Juni";
                break;
            case 7:
                return "Juli";
                break;
            case 8:
                return "Agustus";
                break;
            case 9:
                return "September";
                break;
            case 10:
                return "Oktober";
                break;
            case 11:
                return "November";
                break;
            case 12:
                return "Desember";
                break;
        }
    }

    public function bulan_singkat($bln)
    {
        switch ($bln) {
            case 1:
                return "Jan";
                break;
            case 2:
                return "Feb";
                break;
            case 3:
                return "Mar";
                break;
            case 4:
                return "Apr";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Jun";
                break;
            case 7:
                return "Jul";
                break;
            case 8:
                return "Agust";
                break;
            case 9:
                return "Sept";
                break;
            case 10:
                return "Okt";
                break;
            case 11:
                return "Nov";
                break;
            case 12:
                return "Des";
                break;
        }
    }

    public function nama_hari($tanggal)
    {
        $ubah = gmdate($tanggal, time() + 60 * 60 * 8);
        $pecah = explode("-", $ubah);
        $tgl = $pecah[2];
        $bln = $pecah[1];
        $thn = $pecah[0];

        $nama = date("l", mktime(0, 0, 0, $bln, $tgl, $thn));
        $nama_hari = "";
        if ($nama == "Sunday") {
            $nama_hari = "Minggu";
        } else if ($nama == "Monday") {
            $nama_hari = "Senin";
        } else if ($nama == "Tuesday") {
            $nama_hari = "Selasa";
        } else if ($nama == "Wednesday") {
            $nama_hari = "Rabu";
        } else if ($nama == "Thursday") {
            $nama_hari = "Kamis";
        } else if ($nama == "Friday") {
            $nama_hari = "Jumat";
        } else if ($nama == "Saturday") {
            $nama_hari = "Sabtu";
        }
        return $nama_hari;
    }

    public function hari_id($tanggal)
    {
        $ubah = gmdate($tanggal, time() + 60 * 60 * 8);
        $pecah = explode("-", $ubah);
        $tgl = $pecah[2];
        $bln = $pecah[1];
        $thn = $pecah[0];

        $nama = date("l", mktime(0, 0, 0, $bln, $tgl, $thn));
        $nama_hari = "";
        if ($nama == "Sunday") {
            $nama_hari = 7;
        } else if ($nama == "Monday") {
            $nama_hari = 1;
        } else if ($nama == "Tuesday") {
            $nama_hari = 2;
        } else if ($nama == "Wednesday") {
            $nama_hari = 3;
        } else if ($nama == "Thursday") {
            $nama_hari = 4;
        } else if ($nama == "Friday") {
            $nama_hari = 5;
        } else if ($nama == "Saturday") {
            $nama_hari = 6;
        }
        return $nama_hari;
    }

    public function hitung_mundur($wkt)
    {
        $waktu = array(
            365 * 24 * 60 * 60    => "tahun",
            30 * 24 * 60 * 60        => "bulan",
            7 * 24 * 60 * 60        => "minggu",
            24 * 60 * 60        => "hari",
            60 * 60            => "jam",
            60                => "menit",
            1                => "detik"
        );

        $hitung = strtotime(gmdate("Y-m-d H:i:s", time() + 60 * 60 * 8)) - $wkt;
        $hasil = array();
        if ($hitung < 5) {
            $hasil = 'kurang dari 5 detik yang lalu';
        } else {
            $stop = 0;
            foreach ($waktu as $periode => $satuan) {
                if ($stop >= 6 || ($stop > 0 && $periode < 60)) break;
                $bagi = floor($hitung / $periode);
                if ($bagi > 0) {
                    $hasil[] = $bagi . ' ' . $satuan;
                    $hitung -= $bagi * $periode;
                    $stop++;
                } else if ($stop > 0) $stop++;
            }
            $hasil = implode(' ', $hasil) . ' yang lalu';
        }
        return $hasil;
    }

    public function nomor_indo($bln)
    {
        switch ($bln) {
            case 0:
                return "Nol";
                break;
            case 1:
                return "Satu";
                break;
            case 2:
                return "Dua";
                break;
            case 3:
                return "Tiga";
                break;
            case 4:
                return "Empat";
                break;
            case 5:
                return "Lima";
                break;
            case 6:
                return "Enam";
                break;
            case 7:
                return "Tujuh";
                break;
            case 8:
                return "Delapan";
                break;
            case 9:
                return "Sembilan";
                break;
            case 10:
                return "Sepuluh";
                break;
            case 11:
                return "Sebelas";
                break;
        }
    }

    public function nomor_text($nomor)
    {
        if ($nomor > 0 && $nomor <= 11) {
            return $this->nomor_indo($nomor);
        } elseif ($nomor > 11 && $nomor < 20) {
            $nomor_belakang = substr($nomor, 1, 1);
            return  $this->nomor_indo($nomor_belakang) . " Belas";
        } elseif ($nomor >= 20 && $nomor < 100) {
            $nomor_depan = substr($nomor, 0, 1);
            $nomor_belakang = substr($nomor, 1, 1);
            return  $this->nomor_indo($nomor_depan) . " Puluh" . ($nomor_belakang > 0 ? " " .  $this->nomor_indo($nomor_belakang) : null);
        } elseif (strlen($nomor) == 3) {
            $nomor1 = substr($nomor, 0, 1);
            $nomor2 = substr($nomor, 1, 1);
            $nomor3 = substr($nomor, 2, 1);

            $data = null;
            return $data;
        } elseif (strlen($nomor) == 4) {
            $nomor1 = substr($nomor, 0, 1);
            $nomor2 = substr($nomor, 1, 1);
            $nomor3 = substr($nomor, 2, 1);
            $nomor4 = substr($nomor, 3, 1);

            if ($nomor3 == 1) {
                return  $this->nomor_indo($nomor1) . " Ribu " .  $this->nomor_indo($nomor4) . " Belas";
            } else {
                return  $this->nomor_indo($nomor1) . " Ribu " .  $this->nomor_indo($nomor3) . " Puluh " .  $this->nomor_indo($nomor4);
            }
        }
    }
}
