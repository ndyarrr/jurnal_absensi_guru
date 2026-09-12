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
        'jam_keluar',
        'perkiraan_kembali',
        'jam_kembali_aktual',
        'dicatat_oleh_user_id',
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

    /**
     * Status gerbang: dipakai khusus di halaman Satpam > Cek Izin Siswa.
     * Beda dengan `status` (status approval izin oleh piket/waka/kepsek).
     */
    public function getStatusGerbangAttribute(): string
    {
        if ($this->jam_kembali_aktual) {
            return 'disetujui'; // sudah kembali ke sekolah
        }

        if (!$this->perkiraan_kembali) {
            return 'disetujui'; // izin sekali jalan / tidak kembali (mis. sakit dijemput)
        }

        $now = now('Asia/Jakarta')->format('H:i:s');
        $tanggalIzin = $this->tanggal_mulai;
        $isHariIni = \Carbon\Carbon::parse($tanggalIzin)->isToday();

        if ($isHariIni && $now > $this->perkiraan_kembali) {
            return 'kadaluwarsa';
        }

        return 'menunggu';
    }

    public function getStatusGerbangLabelAttribute(): string
    {
        return match ($this->status_gerbang) {
            'disetujui' => 'Disetujui',
            'kadaluwarsa' => 'Kadaluwarsa',
            default => 'Menunggu Kembali',
        };
    }
}