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
     * Hitung total honor yang sudah dialokasikan untuk mitra pada bulan & tahun tertentu.
     */
    public function totalHonorBulan(int $tahun, int $bulan, ?int $excludeAlokasiId = null): int
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
     * Hitung sisa kuota honor yang masih boleh diterima pada bulan & tahun tertentu.
     */
    public function sisaKuotaHonor(int $tahun, int $bulan, ?int $excludeAlokasiId = null): int
    {
        $maksimal = PengaturanHonorMitra::getMaksimalHonor($tahun, $bulan);
        $terpakai = $this->totalHonorBulan($tahun, $bulan, $excludeAlokasiId);
        $sisa = $maksimal - $terpakai;
        return max(0, $sisa);
    }
}
