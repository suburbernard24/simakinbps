<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitKerja extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'keterangan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function pegawais(): HasMany
    {
        return $this->hasMany(User::class, 'unit_kerja_id');
    }

    public function surats(): HasMany
    {
        return $this->hasMany(Surat::class, 'unit_kerja_id');
    }
}
