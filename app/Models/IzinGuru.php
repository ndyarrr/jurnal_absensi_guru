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

    public function getKategoriLabelAttribute(): string
    {
        return match ($this->kategori_izin) {
            'sakit' => 'Sakit',
            'dinas_luar' => 'Dinas Luar',
            'urusan_keluarga' => 'Urusan Keluarga',
            'pelatihan' => 'Pelatihan / Diklat',
            'lainnya' => 'Lainnya',
            default => 'Izin',
        };
    }
}
