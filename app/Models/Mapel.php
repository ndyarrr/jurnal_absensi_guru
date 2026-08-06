<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use SoftDeletes;
    protected $table = 'mapel';
    protected $primaryKey = 'id_mapel';
    public $timestamps = false;
    protected $fillable = ['nama_mapel'];

    public function getRouteKeyName()
    {
        return 'id_mapel';
    }

    public function jadwal()
    {
        return $this->hasMany(JadwalPelajaran::class, 'id_mapel');
    }

    public function guru()
    {
        return $this->belongsToMany(Guru::class, 'guru_mapel', 'id_guru', 'id_mapel');
    }
}