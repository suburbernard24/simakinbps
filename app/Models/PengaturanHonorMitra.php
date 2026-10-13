<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengaturanHonorMitra extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_honor_mitras';

    /**
     * Klasifikasi jenis mitra yang didukung sistem.
     */
    const JENIS_PENDATAAN  = 'pendataan_lapangan';
    const JENIS_PENGOLAHAN = 'pengolahan';

    /**
     * Default batas honor per jenis mitra (SBM BPS).
     */
    const DEFAULT_PENDATAAN  = 3653000;
    const DEFAULT_PENGOLAHAN = 3379000;

    protected $fillable = [
        'tahun',
        'bulan',
        'maksimal_honor',
        'updated_by',
        'keterangan',
        'jenis_mitra',
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
     * Label human-readable untuk setiap jenis mitra.
     */
    public static function labelJenis(string $jenis): string
    {
        return match($jenis) {
            self::JENIS_PENDATAAN  => 'Mitra Pendataan Lapangan',
            self::JENIS_PENGOLAHAN => 'Mitra Pengolahan',
            default                => ucfirst(str_replace('_', ' ', $jenis)),
        };
    }

    /**
     * Ambil batas maksimal honor mitra berdasarkan jenis mitra & periode.
     * Fallback default sesuai SBM BPS terbaru.
     *
     * @param string   $jenisMitra  'pendataan_lapangan' | 'pengolahan'
     * @param int|null $tahun
     * @param int|null $bulan
     */
    public static function getMaksimalHonor(
        string $jenisMitra = self::JENIS_PENDATAAN,
        ?int $tahun = null,
        ?int $bulan = null
    ): int {
        $base = static::where('jenis_mitra', $jenisMitra);

        // 1. Cek konfigurasi spesifik tahun & bulan
        if ($tahun && $bulan) {
            $setting = (clone $base)->where('tahun', $tahun)->where('bulan', $bulan)->first();
            if ($setting) {
                return (int) $setting->maksimal_honor;
            }
        }

        // 2. Cek konfigurasi tahunan (tanpa bulan)
        if ($tahun) {
            $setting = (clone $base)->where('tahun', $tahun)->whereNull('bulan')->first();
            if ($setting) {
                return (int) $setting->maksimal_honor;
            }
        }

        // 3. Ambil pengaturan global (tanpa tahun & bulan)
        $global = (clone $base)->whereNull('tahun')->whereNull('bulan')->latest('id')->first();
        if ($global) {
            return (int) $global->maksimal_honor;
        }

        // 4. Ambil record terbaru dari jenis ini
        $latest = (clone $base)->latest('id')->first();
        if ($latest) {
            return (int) $latest->maksimal_honor;
        }

        // 5. Default fallback SBM BPS
        return $jenisMitra === self::JENIS_PENGOLAHAN
            ? self::DEFAULT_PENGOLAHAN
            : self::DEFAULT_PENDATAAN;
    }

    /**
     * Backward-compatible: ambil honor mitra pendataan (default) jika kode lama tidak
     * menyertakan parameter jenis_mitra.
     *
     * @deprecated Gunakan getMaksimalHonor(string $jenisMitra, ...) secara eksplisit.
     */
    public static function getMaksimalHonorLegacy(?int $tahun = null, ?int $bulan = null): int
    {
        return self::getMaksimalHonor(self::JENIS_PENDATAAN, $tahun, $bulan);
    }
}
