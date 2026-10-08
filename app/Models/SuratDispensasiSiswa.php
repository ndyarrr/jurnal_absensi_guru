<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratDispensasiSiswa extends Model
{
    protected $table = 'surat_dispensasi_siswa';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'id_dispen',
        'id_siswa',
    ];

    public function suratDispensasi()
    {
        return $this->belongsTo(SuratDispensasi::class, 'id_dispen', 'id_dispen');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

public function siswaList()
{
    return $this->hasMany(
        SuratDispensasiSiswa::class,
        'id_dispen',
        'id_dispen'
    );
}
}