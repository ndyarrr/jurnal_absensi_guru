<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Siswa.php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
  
    use SoftDeletes;

    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    public $timestamps = false;
    protected $fillable = ['nisn', 'nama_siswa', 'id_kelas'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function ketidakhadiran()
    {
        return $this->hasMany(DetailKetidakhadiran::class, 'id_siswa');
    }
}