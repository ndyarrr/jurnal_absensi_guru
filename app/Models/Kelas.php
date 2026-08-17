<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use SoftDeletes;
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    public $timestamps = false;
    protected $fillable = ['tingkat', 'id_jurusan', 'rombel', 'id_guru_wali', 'wali_kelas', 'jumlah_siswa'];

    public function getRouteKeyName()
    {
        return 'id_kelas';
    }

    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'id_guru_wali', 'id_guru')->withTrashed();
    }

    public function waliKelasGuru()
    {
        return $this->belongsTo(Guru::class, 'id_guru_wali', 'id_guru')->withTrashed();
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan')->withTrashed();
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas');
    }

    public function jadwal()
    {
        return $this->hasMany(JadwalPelajaran::class, 'id_kelas');
    }
}