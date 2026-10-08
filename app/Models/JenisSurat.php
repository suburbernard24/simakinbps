<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSurat extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'format_nomor',
        'kode_klasifikasi_default',
        'deskripsi',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function templates(): HasMany
    {
        return $this->hasMany(TemplateSurat::class, 'jenis_surat_id');
    }

    public function surats(): HasMany
    {
        return $this->hasMany(Surat::class, 'jenis_surat_id');
    }

    public function nomorSurats(): HasMany
    {
        return $this->hasMany(NomorSurat::class, 'jenis_surat_id');
    }
}
