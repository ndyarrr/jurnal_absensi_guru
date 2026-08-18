<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangan';
    protected $primaryKey = 'id_ruangan';
    protected $fillable = ['nama_ruangan', 'keterangan'];

    public function jadwal()
    {
        return $this->hasMany(JadwalPelajaran::class, 'ruangan', 'nama_ruangan');
    }
}
