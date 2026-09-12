<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKejadianSiswa extends Model
{
    protected $table = 'laporan_kejadian_siswa';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_siswa',
        'id_kelas',
        'jenis_kejadian',
        'catatan_kejadian',
        'id_user_pelapor',
        'kirim_ke_wali_kelas',
        'kirim_ke_guru_piket',
        'terkirim_wa',
        'status',
    ];

    protected $casts = [
        'kirim_ke_wali_kelas' => 'boolean',
        'kirim_ke_guru_piket' => 'boolean',
        'terkirim_wa' => 'boolean',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas')->withTrashed();
    }

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'id_user_pelapor', 'id');
    }

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis_kejadian) {
            'terlambat_kembali' => 'Terlambat Kembali dari Izin',
            'keluar_tanpa_izin' => 'Keluar Tanpa Izin',
            'pelanggaran_tata_tertib' => 'Pelanggaran Tata Tertib',
            default => 'Lainnya',
        };
    }
}