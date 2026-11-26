<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PstAntrian extends Model
{
    protected $fillable = [
        'tanggal',
        'kode_layanan',
        'nama_layanan',
        'loket',
        'urutan',
        'nomor_antrian',
        'nama_pemohon',
        'no_wa',
        'instansi',
        'keperluan',
        'status',
        'called_at',
    ];

    protected $casts = [
        'tanggal'   => 'date',
        'called_at' => 'datetime',
    ];
}
