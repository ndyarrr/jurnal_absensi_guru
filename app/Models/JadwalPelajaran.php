<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/JadwalPelajaran.php
class JadwalPelajaran extends Model
{
    protected $table = 'jadwal_pelajaran';
    protected $primaryKey = 'id_jadwal';
    public $timestamps = false;
    protected $fillable = ['id_kelas', 'hari', 'jam_ke', 'id_guru', 'id_mapel'];

    public function guru() { return $this->belongsTo(Guru::class, 'id_guru'); }
    public function kelas() { return $this->belongsTo(Kelas::class, 'id_kelas'); }
    public function mapel() { return $this->belongsTo(Mapel::class, 'id_mapel'); }
}
