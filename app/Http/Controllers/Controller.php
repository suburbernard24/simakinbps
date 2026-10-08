<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

abstract class Controller
{
    /**
     * Daftar nama bulan dalam Bahasa Indonesia (index 1–12).
     */
    protected function getDaftarBulan(): array
    {
        return [
            1  => 'Januari',  2  => 'Februari', 3  => 'Maret',
            4  => 'April',    5  => 'Mei',       6  => 'Juni',
            7  => 'Juli',     8  => 'Agustus',   9  => 'September',
            10 => 'Oktober',  11 => 'November',  12 => 'Desember',
        ];
    }

    /**
     * Daftar tahun: dari (tahun sekarang + 1) sampai (tahun sekarang - 4).
     */
    protected function getDaftarTahun(): array
    {
        return range(Carbon::now()->year + 1, Carbon::now()->year - 4);
    }
}

