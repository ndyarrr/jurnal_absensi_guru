<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IzinGuru extends Model
{
    use SoftDeletes;

    protected $table = 'izin_guru';
    protected $primaryKey = 'id_izin_guru';

    protected $fillable = [
        'id_guru',
        'kategori_izin',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'alasan_izin',
        'bukti_surat',
        'status_approval',
        'disetujui_oleh',
        'catatan_approver',
        'approval_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->approval_token)) {
                $model->approval_token = \Illuminate\Support\Str::random(32);
            }
        });
    }

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function getRouteKeyName()
    {
        return 'id_izin_guru';
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru')->withTrashed();
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function getBuktiSuratUrlAttribute(): ?string
    {
        if (!$this->bukti_surat) {
            return null;
        }

        return asset('storage/' . ltrim($this->bukti_surat, '/'));
    }

    public function getApprovalUrlAttribute(): string
    {
        if (!$this->approval_token) {
            return '#';
        }
        return route('izin.approval.show', ['id' => $this->id_izin_guru, 'token' => $this->approval_token]);
    }

    public function getKategoriLabelAttribute(): string
    {
        return match ($this->kategori_izin) {
            'sakit' => 'Sakit',
            'dinas', 'dinas_luar' => 'Dinas',
            'cuti' => 'Cuti',
            'acara_keluarga', 'urusan_keluarga' => 'Acara Keluarga',
            'pelatihan' => 'Pelatihan / Diklat',
            'lainnya' => 'Lainnya',
            default => ucwords(str_replace('_', ' ', $this->kategori_izin ?? 'Izin')),
        };
    }
}
