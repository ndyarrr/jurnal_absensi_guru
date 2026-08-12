<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanIzin extends Model
{
    protected $table = 'permohonan_izin';
    protected $primaryKey = 'id_permohonan';

    protected $fillable = [
        'tipe_pemohon',
        'id_guru',
        'id_siswa',
        'jenis_izin',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'bukti_surat',
        'status',
        'catatan_revisi',
    ];

    public function getRouteKeyName()
    {
        return 'id_permohonan';
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function approvalLogs()
    {
        return $this->hasMany(ApprovalLog::class, 'id_permohonan', 'id_permohonan');
    }
}
