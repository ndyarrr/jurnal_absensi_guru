<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SuratDispensasi extends Model
{
    use SoftDeletes;

    protected $table = 'surat_dispensasi';
    protected $primaryKey = 'id_dispen';

    protected $fillable = [
        'nomor_surat',
        'tipe_pemohon',
        'id_siswa',
        'id_guru',
        'id_kelas',
        'nama_kegiatan',
        'lokasi_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'alasan_dispensasi',
        'file_surat',
        'status_approval',
        'status_waka',
        'disetujui_waka_oleh',
        'tgl_disetujui_waka',
        'status_waka_kurikulum',
        'disetujui_waka_kurikulum_oleh',
        'tgl_disetujui_waka_kurikulum',
        'status_kepsek',
        'disetujui_kepsek_oleh',
        'tgl_disetujui_kepsek',
        'catatan_approver',
        'disetujui_oleh',
        'barcode_token',
        'ttd_siswa_path',
        'ttd_siswa_signed_at',
        'ttd_siswa_signed_name',
        'ttd_guru_path',
        'ttd_guru_signed_at',
        'ttd_guru_signed_name',
    ];

    protected $casts = [
        'ttd_siswa_signed_at' => 'datetime',
        'ttd_guru_signed_at'  => 'datetime',
        'tgl_disetujui_waka'  => 'datetime',
        'tgl_disetujui_waka_kurikulum' => 'datetime',
        'tgl_disetujui_kepsek' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->barcode_token)) {
                $model->barcode_token = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'id_dispen';
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa')->withTrashed();
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru')->withTrashed();
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas')->withTrashed();
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function approverWaka()
    {
        return $this->belongsTo(User::class, 'disetujui_waka_oleh');
    }

    public function approverWakaKurikulum()
    {
        return $this->belongsTo(User::class, 'disetujui_waka_kurikulum_oleh');
    }

    public function approverKepsek()
    {
        return $this->belongsTo(User::class, 'disetujui_kepsek_oleh');
    }

    public function getFileSuratUrlAttribute(): ?string
    {
        if (!$this->file_surat) {
            return null;
        }

        return asset('storage/' . ltrim($this->file_surat, '/'));
    }

    public function getTtdSiswaUrlAttribute(): ?string
    {
        if (!$this->ttd_siswa_path) {
            return null;
        }

        return asset('storage/' . ltrim($this->ttd_siswa_path, '/'));
    }

    public function getTtdGuruUrlAttribute(): ?string
    {
        if (!$this->ttd_guru_path) {
            return null;
        }

        return asset('storage/' . ltrim($this->ttd_guru_path, '/'));
    }

    public function getApprovalUrlAttribute(): string
    {
        if (!$this->barcode_token) {
            return '#';
        }
        return route('dispensasi.approval.show', ['id' => $this->id_dispen, 'token' => $this->barcode_token]);
    }
}
