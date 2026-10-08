<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_tim',
        'singkatan',
        'ketua_id',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function ketua(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ketua_id');
    }

    public function pegawais(): HasMany
    {
        return $this->hasMany(User::class, 'tim_id');
    }

    public function alokasiPekerjaans(): HasMany
    {
        return $this->hasMany(AlokasiPekerjaan::class, 'tim_id');
    }
}
