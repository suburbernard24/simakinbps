<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'nip',
        'jabatan',
        'golongan',
        'email',
        'password',
        'role',
        'foto',
        'is_active',
        'tim_id',
        'is_ketua_tim',
        'is_public_user',
        // e-Surat
        'gelar',
        'pangkat',
        'no_whatsapp',
        'unit_kerja_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'avatar_url',
    ];

    protected function casts(): array
    {
        return [
            'password'       => 'hashed',
            'is_active'      => 'boolean',
            'is_ketua_tim'   => 'boolean',
            'is_public_user' => 'boolean',
        ];
    }

    public function tim(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'tim_id');
    }

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function surats(): HasMany
    {
        return $this->hasMany(Surat::class, 'user_id');
    }

    public function suratTugas(): HasMany
    {
        return $this->hasMany(SuratPegawai::class, 'pegawai_id');
    }

    public function teamLeadOf(): HasOne
    {
        return $this->hasOne(Team::class, 'ketua_id');
    }

    public function alokasiPekerjaans(): HasMany
    {
        return $this->hasMany(AlokasiPekerjaan::class, 'pegawai_id');
    }

    public function alokasiDibuat(): HasMany
    {
        return $this->hasMany(AlokasiPekerjaan::class, 'created_by');
    }

    public function penilaianDiberikan(): HasMany
    {
        return $this->hasMany(AlokasiPekerjaan::class, 'penilai_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKepala(): bool
    {
        return $this->role === 'kepala_bps';
    }

    public function isKetuaTim(): bool
    {
        return $this->role === 'ketua_tim' || $this->is_ketua_tim;
    }

    public function isPegawai(): bool
    {
        return in_array($this->role, ['pegawai', 'anggota_tim']) && !$this->is_ketua_tim;
    }

    public function isVerifikatorRole(): bool
    {
        return false;
    }

    public function isVerifikator(): bool
    {
        return false;
    }

    public function isApprover(): bool
    {
        return in_array($this->role, ['kepala_bps', 'admin']);
    }

    /**
     * Admin dan Kepala Kantor tidak perlu membuat surat.
     * Hanya Pegawai dan Ketua Tim yang membuat naskah dinas / surat.
     */
    public function canCreateSurat(): bool
    {
        return !$this->isAdmin() && !$this->isKepala();
    }

    public function isNonAdmin(): bool
    {
        return $this->role !== 'admin';
    }

    public function isLeadership(): bool
    {
        return in_array($this->role, ['kepala_bps', 'ketua_tim']) || $this->is_ketua_tim;
    }

    /**
     * Cek apakah user (Ketua Tim) boleh mengalokasikan pekerjaan utama kepada target user.
     * Aturan: Hanya boleh mengalokasikan ke diri sendiri ATAU anggota dalam timnya.
     */
    public function canAllocateTo(User $target): bool
    {
        if (!$this->isKetuaTim()) {
            return false;
        }

        // Boleh mengalokasikan ke diri sendiri
        if ($this->id === $target->id) {
            return true;
        }

        // Boleh mengalokasikan ke anggota timnya
        if ($this->tim_id && $target->tim_id === $this->tim_id) {
            return true;
        }

        return false;
    }

    /**
     * Cek kewenangan penilaian:
     * 1. Pekerjaan utama pegawai biasa dinilai oleh Ketua Tim-nya.
     * 2. Ketua Tim dinilai oleh Kepala BPS.
     * 3. Pekerjaan tambahan seluruh pegawai dinilai oleh Kepala BPS.
     * 4. Admin dan Pegawai biasa TIDAK boleh menilai.
     */
    public function canRateTask(AlokasiPekerjaan $task): bool
    {
        if ($this->isAdmin() || $this->isPegawai()) {
            return false;
        }

        $assignedUser = $task->pegawai;
        if (!$assignedUser) {
            return false;
        }

        // Kepala BPS berwenang menilai:
        // - Pekerjaan Tambahan seluruh pegawai
        // - Pekerjaan Utama milik Ketua Tim
        if ($this->isKepala()) {
            if ($task->jenis_pekerjaan === 'tambahan') {
                return true;
            }
            if ($task->jenis_pekerjaan === 'utama' && $assignedUser->isKetuaTim()) {
                return true;
            }
            return false;
        }

        // Ketua Tim berwenang menilai:
        // - Pekerjaan Utama milik anggota timnya (BUKAN dirinya sendiri)
        if ($this->isKetuaTim()) {
            if ($task->jenis_pekerjaan === 'utama') {
                if ($assignedUser->id === $this->id) {
                    // Diri sendiri dinilai Kepala BPS
                    return false;
                }
                if ($this->tim_id && $assignedUser->tim_id === $this->tim_id) {
                    return true;
                }
            }
            return false;
        }

        return false;
    }

    public function canManageMasterMitra(): bool
    {
        return $this->isKetuaTim();
    }

    public function canManageAlokasiMitra(): bool
    {
        return $this->isKetuaTim();
    }

    public function canRateMitra(): bool
    {
        return $this->isKetuaTim() || $this->isPegawai();
    }

    public function canViewMitra(): bool
    {
        return $this->isNonAdmin();
    }

    public static function getKepala(): ?self
    {
        return static::where('role', 'kepala_bps')->where('is_active', true)->first();
    }

    public function getRoleDisplayAttribute(): string
    {
        if ($this->is_public_user) {
            return 'User Publik';
        }

        return match($this->role) {
            'admin'                  => 'Administrator',
            'kepala_bps'             => 'Kepala BPS',
            'ketua_tim'              => 'Ketua Tim',
            'anggota_tim', 'pegawai' => 'Anggota Tim',
            default                  => ucfirst(str_replace('_', ' ', $this->role)),
        };
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->foto && file_exists(public_path('storage/' . $this->foto))) {
            return asset('storage/' . $this->foto);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1565C0&color=ffffff&bold=true';
    }
}
