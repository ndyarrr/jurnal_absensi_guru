<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamPelajaran extends Model
{
    protected $table = 'jam_pelajaran';
    protected $primaryKey = 'id_jam';
    
    protected $fillable = [
        'hari_kategori',
        'jam_ke',
        'jam_mulai',
        'jam_selesai',
        'is_istirahat',
        'durasi_menit',
        'keterangan',
    ];

    public function getRouteKeyName()
    {
        return 'id_jam';
    }

    public function jadwal()
    {
        return $this->hasMany(JadwalPelajaran::class, 'id_jam', 'id_jam');
    }
}
