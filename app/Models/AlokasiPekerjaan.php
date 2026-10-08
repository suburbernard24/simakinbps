<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlokasiPekerjaan extends Model
{
    use HasFactory;

    protected $table = 'alokasi_pekerjaans';

    protected $fillable = [
        'nama_kegiatan',
        'satuan',
        'jenis_pekerjaan',
        'target_kuantitas',
        'realisasi_kuantitas',
        'persentase_kuantitas',
        'asal_tim',
        'tim_id',
        'pegawai_id',
        'created_by',
        'keterangan',
        'estimasi_waktu',
        'tanggal_mulai',
        'tanggal_selesai',
        'tahun',
        'bulan',
        'status',
        'catatan_realisasi',
        'link_bukti',
        'tanggal_realisasi',
        'kualitas',
        'nilai_ckp',
        'penilai_id',
        'catatan_penilai',
        'tanggal_penilaian',
        'status_penilaian',
    ];

    protected $casts = [
        'tanggal_mulai'        => 'date:Y-m-d',
        'tanggal_selesai'      => 'date:Y-m-d',
        'tanggal_realisasi'    => 'date:Y-m-d',
        'tanggal_penilaian'    => 'datetime',
        'target_kuantitas'     => 'decimal:2',
        'realisasi_kuantitas'  => 'decimal:2',
        'persentase_kuantitas' => 'decimal:2',
        'kualitas'             => 'decimal:2',
        'nilai_ckp'            => 'decimal:2',
        'estimasi_waktu'       => 'decimal:2',
        'tahun'                => 'integer',
        'bulan'                => 'integer',
    ];

    protected $appends = [
        'skor_kinerja',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pegawai_id');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function penilai(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penilai_id');
    }

    public function tim(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'tim_id');
    }

    public function scopeUtama($query)
    {
        return $query->where('jenis_pekerjaan', 'utama');
    }

    public function scopeTambahan($query)
    {
        return $query->where('jenis_pekerjaan', 'tambahan');
    }

    public function scopePeriode($query, $tahun = null, $bulan = null)
    {
        if ($tahun) {
            $query->where('tahun', $tahun);
        }
        if ($bulan) {
            $query->where('bulan', $bulan);
        }
        return $query;
    }

    public function getNamaBulanAttribute(): string
    {
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return $bulanList[$this->bulan] ?? '-';
    }

    /**
     * Hitung Skor Kinerja berdasarkan rumus:
     * Estimasi Waktu (JAM) / Total Jam Kerja Aktif Bulanan.
     */
    public function getSkorKinerjaAttribute(): float
    {
        $tahun = $this->tahun ?: ($this->tanggal_mulai ? $this->tanggal_mulai->year : Carbon::now()->year);
        $bulan = $this->bulan ?: ($this->tanggal_mulai ? $this->tanggal_mulai->month : Carbon::now()->month);

        $jamAktif = HariKerjaAktif::getJamKerjaAktif($tahun, $bulan);

        if ($jamAktif <= 0) {
            return 0;
        }

        // Estimasi jam / total jam aktif bulanan
        return round(($this->estimasi_waktu / $jamAktif) * 100, 2);
    }

    protected static function booted()
    {
        static::saving(function ($task) {
            // Set tahun dan bulan jika belum ada
            if (empty($task->tahun) && $task->tanggal_mulai) {
                $task->tahun = Carbon::parse($task->tanggal_mulai)->year;
            }
            if (empty($task->bulan) && $task->tanggal_mulai) {
                $task->bulan = Carbon::parse($task->tanggal_mulai)->month;
            }

            // Hitung Persentase Kuantitas (a)
            if ($task->target_kuantitas > 0) {
                $task->persentase_kuantitas = round(($task->realisasi_kuantitas / $task->target_kuantitas) * 100, 2);
            } else {
                $task->persentase_kuantitas = 0;
            }

            // Hitung CKP jika Kualitas (b) sudah diisi
            if ($task->kualitas > 0) {
                $task->nilai_ckp = round(($task->persentase_kuantitas + $task->kualitas) / 2, 2);
                if (empty($task->status_penilaian) || $task->status_penilaian === 'belum_dinilai') {
                    $task->status_penilaian = 'sudah_dinilai';
                }
            } else {
                $task->nilai_ckp = 0;
                if (empty($task->status_penilaian)) {
                    $task->status_penilaian = 'belum_dinilai';
                }
            }
        });
    }
}
