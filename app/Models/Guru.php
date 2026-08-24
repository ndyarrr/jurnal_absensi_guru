<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

// app/Models/Guru.php
class Guru extends Model
{
    use SoftDeletes;
    protected $table = 'guru';
    protected $primaryKey = 'id_guru';
    public $timestamps = false;
    protected $fillable = ['nuptk', 'nama_guru', 'no_hp'];

    public function jadwal()
    {
        return $this->hasMany(JadwalPelajaran::class, 'id_guru');
    }

    public function mapel()
    {
        return $this->belongsToMany(Mapel::class, 'guru_mapel', 'id_guru', 'id_mapel');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id_guru', 'id_guru');
    }

    public function kelasWali()
    {
        return $this->hasOne(Kelas::class, 'id_guru_wali', 'id_guru');
    }

    public function permohonanIzin()
    {
        return $this->hasMany(PermohonanIzin::class, 'id_guru', 'id_guru');
    }

    public function izin()
    {
        return $this->hasMany(IzinGuru::class, 'id_guru', 'id_guru');
    }
}