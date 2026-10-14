<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontendIndikator extends Model
{
    protected $table = 'frontend_indikators';

    protected $fillable = [
        'judul',
        'sub_judul',
        'nilai',
        'periode',
        'deskripsi',
        'badge_text',
        'link_tabel',
        'icon',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
