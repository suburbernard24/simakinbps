<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianMitra extends Model
{
    use HasFactory;

    protected $table = 'penilaian_mitras';

    protected $fillable = [
        'alokasi_mitra_id',
        'mitra_id',
        'penilai_id',
        'bintang',
        'komentar',
    ];

    protected $casts = [
        'bintang' => 'integer',
    ];

    public function alokasiMitra(): BelongsTo
    {
        return $this->belongsTo(AlokasiMitra::class, 'alokasi_mitra_id');
    }

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    public function penilai(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penilai_id');
    }
}
