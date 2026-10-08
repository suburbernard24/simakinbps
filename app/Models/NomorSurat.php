<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NomorSurat extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_id',
        'jenis_surat_id',
        'tahun',
        'nomor_urut',
        'kode_satker',
        'kode_klasifikasi',
        'nomor_lengkap',
    ];

    public function surat(): BelongsTo
    {
        return $this->belongsTo(Surat::class, 'surat_id');
    }

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }
}
