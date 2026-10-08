<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_surat_id',
        'template_surat_id',
        'user_id',
        'unit_kerja_id',
        'nomor_surat',
        'tanggal_pengajuan',
        'tanggal_surat',
        'perihal',
        'fungsi',
        'status',
        'sumber_surat',
        'file_surat',
        'nama_file_asli',
        'catatan',
        'kode_klasifikasi',
        'kode_satker',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'date',
            'tanggal_surat'     => 'date',
        ];
    }

    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    public function templateSurat(): BelongsTo
    {
        return $this->belongsTo(TemplateSurat::class, 'template_surat_id');
    }

    public function pemohon(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function detail(): HasOne
    {
        return $this->hasOne(SuratDetail::class, 'surat_id');
    }

    public function pegawais(): HasMany
    {
        return $this->hasMany(SuratPegawai::class, 'surat_id')->orderBy('urutan');
    }

    public function penerimas(): HasMany
    {
        return $this->hasMany(SuratPenerima::class, 'surat_id');
    }

    public function lampirans(): HasMany
    {
        return $this->hasMany(SuratLampiran::class, 'surat_id');
    }

    public function verifikasis(): HasMany
    {
        return $this->hasMany(SuratVerifikasi::class, 'surat_id')->orderBy('created_at', 'desc');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(SuratStatusHistory::class, 'surat_id')->orderBy('created_at', 'asc');
    }

    public function nomorSuratRecord(): HasOne
    {
        return $this->hasOne(NomorSurat::class, 'surat_id');
    }

    // Status helpers
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isDikembalikan(): bool
    {
        return $this->status === 'dikembalikan';
    }

    public function canEdit(): bool
    {
        return in_array($this->status, ['draft', 'dikembalikan']);
    }

    public function isSelesai(): bool
    {
        return in_array($this->status, ['selesai', 'diarsipkan']);
    }

    public function isDibuatDiLuar(): bool
    {
        return $this->sumber_surat === 'luar';
    }

    public function isDibuatDiSistem(): bool
    {
        return $this->sumber_surat !== 'luar';
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'draft'         => ['label' => 'Draft', 'bg' => 'bg-slate-100 text-slate-700 border-slate-300'],
            'diajukan'      => ['label' => 'Diajukan', 'bg' => 'bg-sky-50 text-sky-700 border-sky-300'],
            'verifikasi'    => ['label' => 'Menunggu Verifikasi', 'bg' => 'bg-blue-50 text-blue-700 border-blue-300'],
            'dikembalikan'  => ['label' => 'Dikembalikan', 'bg' => 'bg-amber-50 text-amber-700 border-amber-300'],
            'terverifikasi' => ['label' => 'Terverifikasi', 'bg' => 'bg-indigo-50 text-indigo-700 border-indigo-300'],
            'persetujuan'   => ['label' => 'Menunggu Persetujuan', 'bg' => 'bg-purple-50 text-purple-700 border-purple-300'],
            'ditolak'       => ['label' => 'Ditolak', 'bg' => 'bg-rose-50 text-rose-700 border-rose-300'],
            'disetujui'     => ['label' => 'Disetujui', 'bg' => 'bg-teal-50 text-teal-700 border-teal-300'],
            'dinomori'      => ['label' => 'Dinomori', 'bg' => 'bg-emerald-50 text-emerald-700 border-emerald-300'],
            'selesai'       => ['label' => 'Selesai', 'bg' => 'bg-emerald-100 text-emerald-800 border-emerald-400 font-bold'],
            'diarsipkan'    => ['label' => 'Diarsipkan', 'bg' => 'bg-slate-200 text-slate-800 border-slate-400'],
            default         => ['label' => ucfirst($this->status), 'bg' => 'bg-slate-100 text-slate-700 border-slate-300'],
        };
    }
}
