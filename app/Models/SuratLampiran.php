<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratLampiran extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_id',
        'nama_file',
        'file_path',
        'jenis_file',
        'ukuran',
        'uploaded_by',
    ];

    public function surat(): BelongsTo
    {
        return $this->belongsTo(Surat::class, 'surat_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getDownloadUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
