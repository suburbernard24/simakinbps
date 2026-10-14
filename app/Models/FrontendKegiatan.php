<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontendKegiatan extends Model
{
    protected $table = 'frontend_kegiatans';

    protected $fillable = [
        'judul',
        'kategori',
        'tanggal_display',
        'deskripsi',
        'badge_color',
        'icon',
        'link_url',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
