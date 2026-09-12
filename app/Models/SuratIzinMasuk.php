<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratIzinMasuk extends Model
{
    use SoftDeletes;

    protected $table = 'surat_izin_masuk';
    protected $primaryKey = 'id_surat_izin_masuk';

    protected $fillable = [
        'nomor_surat',
        'id_siswa',
        'id_kelas',
        'nama_siswa',
        'kelas_str',
        'jam_pelajaran_ke',
        'alasan',
        'jenis_surat',
        'tanggal',
        'nama_piket_wakasek',
        'nama_guru_piket',
        'id_user_piket',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa')->withTrashed();
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas')->withTrashed();
    }
}
