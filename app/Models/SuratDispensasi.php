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
        'disetujui_oleh',
        'barcode_token',
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

    public function getFileSuratUrlAttribute(): ?string
    {
        if (!$this->file_surat) {
            return null;
        }

        return asset('storage/' . ltrim($this->file_surat, '/'));
    }
}
