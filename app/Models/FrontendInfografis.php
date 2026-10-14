<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontendInfografis extends Model
{
    protected $table = 'frontend_infografis';

    protected $fillable = [
        'judul',
        'kategori',
        'badge_text',
        'bg_gradient',
        'icon',
        'data_points',
        'sumber',
        'tahun',
        'image_path',
        'link_url',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getDataPointsArrayAttribute(): array
    {
        if (empty($this->data_points)) {
            return [];
        }
        $decoded = json_decode($this->data_points, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }
}
