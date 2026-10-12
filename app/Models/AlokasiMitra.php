<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AlokasiMitra extends Model
{
    use HasFactory;

    protected $table = 'alokasi_mitras';

    protected $fillable = [
        'mitra_id',
        'tim_id',
        'created_by',
        'nama_kegiatan',
        'kegiatan',
        'posisi',
        'satuan',
        'target_kuantitas',
        'honor',
        'tanggal_mulai',
        'tanggal_selesai',
        'tahun',
        'bulan',
        'status',
        'provinsi',
        'kabupaten',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_mulai'    => 'date',
        'tanggal_selesai'  => 'date',
        'target_kuantitas' => 'float',
        'honor'            => 'integer',
        'tahun'            => 'integer',
        'bulan'            => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function ($item) {
            if ($item->tanggal_mulai) {
                $tgl = Carbon::parse($item->tanggal_mulai);
                if (empty($item->tahun)) {
                    $item->tahun = $tgl->year;
                }
                if (empty($item->bulan)) {
                    $item->bulan = $tgl->month;
                }
            }
        });
    }

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    public function tim(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'tim_id');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function penilaians(): HasMany
    {
        return $this->hasMany(PenilaianMitra::class, 'alokasi_mitra_id');
    }

    public function penilaianTerbaru(): HasOne
    {
        return $this->hasOne(PenilaianMitra::class, 'alokasi_mitra_id')->latestOfMany();
    }

    public function penilaianOleh($userId): ?PenilaianMitra
    {
        $id = $userId instanceof User ? $userId->id : $userId;
        return $this->penilaians->firstWhere('penilai_id', $id);
    }
}
