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
        'email',
        'password',
        'role',
        'id_guru',
        'avatar',
    ];

    protected $appends = [
        'role_label',
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
            'email_verified_at' => 'datetime',
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
