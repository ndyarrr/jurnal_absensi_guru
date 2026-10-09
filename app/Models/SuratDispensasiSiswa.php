<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratDispensasiSiswa extends Model
{
    protected $table = 'surat_dispensasi_siswa';

    /**
     * Tabel surat_dispensasi_siswa tidak memiliki
     * kolom created_at dan updated_at.
     */
    public $timestamps = false;

    protected $fillable = [
        'id_dispen',
        'id_siswa',
    ];

    /**
     * Relasi ke siswa
     */
    public function siswa()
    {
        return $this->belongsTo(
            Siswa::class,
            'id_siswa',
            'id_siswa'
        );
    }

    /**
     * Relasi ke surat dispensasi
     */
    public function surat()
    {
        return $this->belongsTo(
            SuratDispensasi::class,
            'id_dispen',
            'id_dispen'
        );
    }
}