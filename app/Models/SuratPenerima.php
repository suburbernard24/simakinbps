<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratPenerima extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_id',
        'nama_penerima',
        'instansi',
        'alamat',
        'email',
        'jenis_penerima',
    ];

    public function surat(): BelongsTo
    {
        return $this->belongsTo(Surat::class, 'surat_id');
    }
}
