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
    protected $fillable = ['tingkat', 'id_jurusan', 'rombel', 'wali_kelas', 'jumlah_siswa'];

    // supaya route model binding {kela} mencari berdasarkan id_kelas, bukan id
    public function getRouteKeyName()
    {
        return 'id_kelas';
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan');
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