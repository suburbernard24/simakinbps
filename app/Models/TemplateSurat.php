<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateSurat extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_surat_id',
        'nama_template',
        'kode_template',
        'kop_surat',
        'isi_template',
        'penutup',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    public function surats(): HasMany
    {
        return $this->hasMany(Surat::class, 'template_surat_id');
    }
}
