<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use SoftDeletes;
    protected $table = 'jurusan';
    protected $primaryKey = 'id_jurusan';
    public $timestamps = false;
    protected $fillable = ['kode_jurusan', 'nama_jurusan'];

    public function getRouteKeyName()
    {
        return 'id_jurusan';
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_jurusan');
    }
}