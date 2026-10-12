<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengaturanHonorMitra extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_honor_mitras';

    protected $fillable = [
        'tahun',
        'bulan',
        'maksimal_honor',
        'updated_by',
        'keterangan',
    ];

    protected $casts = [
        'tahun'          => 'integer',
        'bulan'          => 'integer',
        'maksimal_honor' => 'integer',
    ];

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Ambil batas maksimal honor mitra untuk periode tertentu atau nilai global.
     * Default: Rp 4.500.000 / bulan jika belum diset.
     */
    public static function getMaksimalHonor(?int $tahun = null, ?int $bulan = null): int
    {
        // 1. Cek konfigurasi spesifik tahun & bulan
        if ($tahun && $bulan) {
            $setting = static::where('tahun', $tahun)->where('bulan', $bulan)->first();
            if ($setting) {
                return (int) $setting->maksimal_honor;
            }
        }

        // 2. Cek konfigurasi tahunan
        if ($tahun) {
            $setting = static::where('tahun', $tahun)->whereNull('bulan')->first();
            if ($setting) {
                return (int) $setting->maksimal_honor;
            }
        }

        // 3. Cek pengaturan paling mutakhir yang tersedia
        $latest = static::latest('id')->first();
        if ($latest) {
            return (int) $latest->maksimal_honor;
        }

        // 4. Default fallback BPS standard (Rp 4.500.000)
        return 4500000;
    }
}
