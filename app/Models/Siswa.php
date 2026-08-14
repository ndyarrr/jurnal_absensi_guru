<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use SoftDeletes;

    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    public $timestamps = false;
    protected $fillable = ['nisn', 'nama_siswa', 'id_kelas'];

    public function getRouteKeyName()
    {
        return 'id_siswa';
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas')->withTrashed();
    }

    public function ketidakhadiran()
    {
        return $this->hasMany(DetailKetidakhadiran::class, 'id_siswa');
    }
}