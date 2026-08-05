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
}