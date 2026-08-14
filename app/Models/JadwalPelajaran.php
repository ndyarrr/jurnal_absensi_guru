<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPelajaran extends Model
{
    protected $table = 'jadwal_pelajaran';
    protected $primaryKey = 'id_jadwal';
    public $timestamps = false;
    protected $fillable = ['id_kelas', 'hari', 'jam_ke', 'id_jam', 'id_guru', 'id_mapel', 'ruangan'];

    public function getRouteKeyName()
    {
        return 'id_jadwal';
    }

    public function jamPelajaran()
    {
        return $this->belongsTo(JamPelajaran::class, 'id_jam', 'id_jam');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru')->withTrashed();
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas')->withTrashed();
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel')->withTrashed();
    }

    public function jurnal()
    {
        return $this->hasMany(JurnalMengajar::class, 'id_jadwal');
    }
}