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
    protected $fillable = ['nisn', 'nama_siswa', 'jenis_kelamin', 'no_telepon', 'id_kelas'];

    public function getRouteKeyName()
    {
        return 'id_siswa';
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas')->withTrashed();
    }

    /** Akun orang tua yang ditautkan ke siswa ini. */
    public function orangTua()
    {
        return $this->belongsToMany(User::class, 'orang_tua_siswa', 'id_siswa', 'id_user', 'id_siswa', 'id')
            ->withPivot('hubungan')
            ->withTimestamps();
    }

    public function ketidakhadiran()
    {
        return $this->hasMany(DetailKetidakhadiran::class, 'id_siswa');
    }
}