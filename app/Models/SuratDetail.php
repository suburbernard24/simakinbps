<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_id',
        'tanggal_kegiatan',
        'tanggal_selesai',
        'tempat_kegiatan',
        'tujuan_kegiatan',
        'uraian_kegiatan',
        'nomor_sk',
        'tanggal_sk',
        'pejabat_penetap',
        'dasar_penetapan',
        'pihak_pertama',
        'pihak_kedua',
        'jabatan_pihak_pertama',
        'jabatan_pihak_kedua',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kegiatan' => 'date',
            'tanggal_selesai'  => 'date',
            'tanggal_sk'       => 'date',
        ];
    }

    public function surat(): BelongsTo
    {
        return $this->belongsTo(Surat::class, 'surat_id');
    }
}
