<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HariKerjaAktif extends Model
{
    use HasFactory;

    protected $table = 'hari_kerja_aktifs';

    protected $fillable = [
        'tahun',
        'bulan',
        'jumlah_hari_kerja',
        'jam_kerja_per_hari',
        'keterangan',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'bulan' => 'integer',
        'jumlah_hari_kerja' => 'integer',
        'jam_kerja_per_hari' => 'decimal:2',
    ];

    /**
     * Hitung total jam kerja aktif bulanan atau tahunan untuk periode tertentu.
     * Default fallback: 22 hari * 7.5 jam = 165 jam jika belum diinput oleh Admin.
     */
    public static function getJamKerjaAktif(int $tahun, $bulan): float
    {
        if ($bulan === 'all' || $bulan === null || $bulan === 0) {
            $records = static::where('tahun', $tahun)->get();
            if ($records->count() > 0) {
                return (float) $records->sum(function($r) {
                    return $r->jumlah_hari_kerja * $r->jam_kerja_per_hari;
                });
            }
            return 12 * 22.0 * 7.5; // 1 tahun default
        }

        $record = static::where('tahun', $tahun)->where('bulan', $bulan)->first();

        if ($record) {
            return (float) ($record->jumlah_hari_kerja * $record->jam_kerja_per_hari);
        }

        // Fallback default: 22 hari * 7.5 jam = 165 jam
        return 22.0 * 7.5;
    }

    /**
     * Dapatkan jumlah hari kerja aktif.
     */
    public static function getHariKerja(int $tahun, $bulan): int
    {
        if ($bulan === 'all' || $bulan === null || $bulan === 0) {
            $records = static::where('tahun', $tahun)->get();
            if ($records->count() > 0) {
                return (int) $records->sum('jumlah_hari_kerja');
            }
            return 12 * 22; // 1 tahun default
        }

        $record = static::where('tahun', $tahun)->where('bulan', $bulan)->first();
        return $record ? (int) $record->jumlah_hari_kerja : 22;
    }

    public function getNamaBulanAttribute(): string
    {
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return $bulanList[$this->bulan] ?? '-';
    }

    public function getTotalJamAttribute(): float
    {
        return round($this->jumlah_hari_kerja * $this->jam_kerja_per_hari, 1);
    }
}
