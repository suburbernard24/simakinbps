<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mitra extends Model
{
    use HasFactory;

    protected $fillable = [
        'sobat_id',
        'nik',
        'nama',
        'foto',
        'jenis_kelamin',
        'email',
        'telepon',
        'alamat',
        'provinsi',
        'kabupaten',
        'posisi_default',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'foto_url',
        'rata_rata_bintang',
        'total_ulasan',
    ];

    public function alokasiMitras(): HasMany
    {
        return $this->hasMany(AlokasiMitra::class, 'mitra_id');
    }

    public function penilaians(): HasMany
    {
        return $this->hasMany(PenilaianMitra::class, 'mitra_id');
    }

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto && file_exists(public_path('storage/' . $this->foto))) {
            return asset('storage/' . $this->foto);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama) . '&background=003087&color=ffffff&bold=true';
    }

    public function getRataRataBintangAttribute(): float
    {
        $avg = $this->penilaians()->avg('bintang');
        return $avg ? round((float) $avg, 1) : 0.0;
    }

    public function getTotalUlasanAttribute(): int
    {
        return $this->penilaians()->count();
    }

    /**
     * Hitung total honor yang dibayarkan untuk mitra pada bulan & tahun tertentu (memotong kuota batas honor).
     */
    public function totalHonorBulan(int $tahun, int $bulan, ?int $excludeAlokasiId = null): int
    {
        $query = $this->alokasiMitras()
            ->where('tahun', $tahun)
            ->where('bulan', $bulan);

        if ($excludeAlokasiId) {
            $query->where('id', '!=', $excludeAlokasiId);
        }

        return (int) $query->sum(\Illuminate\Support\Facades\DB::raw('COALESCE(honor_dibayar, honor)'));
    }

    /**
     * Hitung total nilai nominal seluruh kegiatan mitra (termasuk yang melebihi batas).
     */
    public function totalHonorNominalBulan(int $tahun, int $bulan, ?int $excludeAlokasiId = null): int
    {
        $query = $this->alokasiMitras()
            ->where('tahun', $tahun)
            ->where('bulan', $bulan);

        if ($excludeAlokasiId) {
            $query->where('id', '!=', $excludeAlokasiId);
        }

        return (int) $query->sum('honor');
    }

    /**
     * Hitung total honor yang tidak dibayar karena melebihi batas kuota maksimum.
     */
    public function totalHonorTidakDibayarBulan(int $tahun, int $bulan, ?int $excludeAlokasiId = null): int
    {
        $query = $this->alokasiMitras()
            ->where('tahun', $tahun)
            ->where('bulan', $bulan);

        if ($excludeAlokasiId) {
            $query->where('id', '!=', $excludeAlokasiId);
        }

        return (int) $query->sum('honor_tidak_dibayar');
    }

    /**
     * Hitung sisa kuota honor yang masih boleh diterima pada bulan & tahun tertentu.
     *
     * @param string $jenisMitra 'pendataan_lapangan' | 'pengolahan'
     */
    public function sisaKuotaHonor(
        int $tahun,
        int $bulan,
        ?int $excludeAlokasiId = null,
        string $jenisMitra = PengaturanHonorMitra::JENIS_PENDATAAN
    ): int {
        $maksimal = PengaturanHonorMitra::getMaksimalHonor($jenisMitra, $tahun, $bulan);
        $terpakai = $this->totalHonorBulan($tahun, $bulan, $excludeAlokasiId);
        $sisa = $maksimal - $terpakai;
        return max(0, $sisa);
    }

    /**
     * Ambil rincian lengkap kuota honor mitra untuk bulan & tahun tertentu.
     */
    public function infoKuotaHonor(
        int $tahun,
        int $bulan,
        string $jenisMitra = PengaturanHonorMitra::JENIS_PENDATAAN,
        ?int $excludeAlokasiId = null
    ): array {
        $maksimal = PengaturanHonorMitra::getMaksimalHonor($jenisMitra, $tahun, $bulan);
        $terpakai = $this->totalHonorBulan($tahun, $bulan, $excludeAlokasiId);
        $sisa = max(0, $maksimal - $terpakai);
        $tidakDibayar = $this->totalHonorTidakDibayarBulan($tahun, $bulan, $excludeAlokasiId);
        $nominalTotal = $this->totalHonorNominalBulan($tahun, $bulan, $excludeAlokasiId);

        return [
            'maksimal'      => $maksimal,
            'terpakai'      => $terpakai,
            'sisa'          => $sisa,
            'tidak_dibayar' => $tidakDibayar,
            'nominal_total' => $nominalTotal,
        ];
    }
}
