<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratVerifikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_id',
        'user_id',
        'level',
        'status',
        'catatan',
        'tanggal_verifikasi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_verifikasi' => 'datetime',
        ];
    }

    public function surat(): BelongsTo
    {
        return $this->belongsTo(Surat::class, 'surat_id');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
