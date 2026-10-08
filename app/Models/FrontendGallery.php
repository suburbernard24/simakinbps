<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontendGallery extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'image_path',
        'kategori',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }

    public function getKategoriLabelAttribute(): string
    {
        return match($this->kategori) {
            'kegiatan'     => 'Kegiatan',
            'dokumentasi'  => 'Dokumentasi',
            'prestasi'     => 'Prestasi',
            default        => ucfirst($this->kategori),
        };
    }
}
