<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Guru;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'password',
        'role',
        'id_guru',
        'avatar',
    ];

    protected $appends = [
        'role_label',
        'role_badges',
        'avatar_url',
        'avatar_initial',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /* ==========================================================================
       Role Helper Methods
       ========================================================================== */

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin'], true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isGuruMengajar(): bool
    {
        return $this->role === 'guru_mengajar';
    }

    public function isWaliKelas(): bool
    {
        return $this->role === 'wali_kelas';
    }

    public function isGuruPiket(): bool
    {
        return $this->role === 'guru_piket';
    }

    public function isKepalaSekolah(): bool
    {
        return $this->role === 'kepala_sekolah';
    }

    public function isWaka(): bool
    {
        return $this->role === 'waka';
    }

    public function isWakaSdm(): bool
    {
        return $this->role === 'waka_sdm';
    }

    public function isSatpam(): bool
    {
        return $this->role === 'satpam';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    /* ==========================================================================
       Relationships
       ========================================================================== */

    /**
     * Relasi ke profil guru (untuk user dengan role guru).
     * Admin/Satpam mungkin tidak memiliki profil guru (id_guru = null).
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'super_admin' => 'Admin Super',
            'admin' => 'Admin Biasa',
            'guru_mengajar' => 'Guru Mengajar',
            'wali_kelas' => 'Wali Kelas',
            'guru_piket' => 'Guru Piket',
            'kepala_sekolah' => 'Kepala Sekolah',
            'waka' => 'Waka',
            'waka_sdm' => 'Waka SDM',
            'satpam' => 'Satpam',
            default => 'Guru Mengajar',
        };
    }

    /**
     * Dynamic Multi-Role Badges (Akun Utama + Wali Kelas + Penugasan Piket).
     */
    public function getRoleBadgesAttribute(): array
    {
        $badges = [];

        // 1. Primary Role Badge
        $primaryLabel = match ($this->role) {
            'super_admin' => 'Admin Super',
            'admin' => 'Admin',
            'guru_mengajar' => 'Guru Mapel',
            'wali_kelas' => 'Wali Kelas',
            'guru_piket' => 'Guru Piket',
            'kepala_sekolah' => 'Kepsek',
            'waka' => 'Waka',
            'waka_sdm' => 'Waka SDM',
            'satpam' => 'Satpam',
            default => 'Guru Mapel',
        };

        $primaryStyle = match ($this->role) {
            'super_admin' => 'background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5;',
            'admin' => 'background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd;',
            'guru_mengajar' => 'background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;',
            'wali_kelas' => 'background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;',
            'guru_piket' => 'background: #faf5ff; color: #9333ea; border: 1px solid #e9d5ff;',
            default => 'background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;',
        };

        $badges[] = [
            'label' => $primaryLabel,
            'style' => $primaryStyle,
            'is_primary' => true,
        ];

        // 2. Check dynamic teacher roles (Wali Kelas assignment & Piket Duty assignment)
        $idGuru = $this->id_guru ?: optional($this->guru)->id_guru;
        if (!$idGuru && !empty($this->name)) {
            $matched = \App\Models\Guru::where('nama_guru', $this->name)->first();
            if ($matched) {
                $idGuru = $matched->id_guru;
            }
        }

        if ($idGuru) {
            // Check if assigned as Wali Kelas to a class
            if ($this->role !== 'wali_kelas') {
                $kelasWali = \App\Models\Kelas::where('id_guru_wali', $idGuru)->first();
                if ($kelasWali) {
                    $kelasName = $kelasWali->tingkat . ' ' . optional($kelasWali->jurusan)->kode_jurusan . ' ' . $kelasWali->rombel;
                    $badges[] = [
                        'label' => 'Wali (' . $kelasName . ')',
                        'style' => 'background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;',
                        'is_primary' => false,
                    ];
                }
            }

            // Check if assigned to Jadwal Piket
            if ($this->role !== 'guru_piket' && \Illuminate\Support\Facades\Schema::hasTable('jadwal_piket')) {
                $piketHari = \App\Models\JadwalPiket::where('id_guru', $idGuru)->pluck('hari')->toArray();
                if (!empty($piketHari)) {
                    $hariStr = implode(', ', $piketHari);
                    $badges[] = [
                        'label' => 'Piket (' . $hariStr . ')',
                        'style' => 'background: #faf5ff; color: #9333ea; border: 1px solid #e9d5ff;',
                        'is_primary' => false,
                    ];
                }
            }
        }

        return $badges;
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if (! $this->avatar) {
            return null;
        }

        return asset('storage/' . ltrim($this->avatar, '/'));
    }

    public function getAvatarInitialAttribute(): string
    {
        $name = trim((string) $this->name);

        return strtoupper(mb_substr($name !== '' ? $name : 'A', 0, 1));
    }
}
