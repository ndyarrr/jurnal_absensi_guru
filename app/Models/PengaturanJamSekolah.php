<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanJamSekolah extends Model
{
    protected $table = 'pengaturan_jam_sekolah';

    protected $fillable = [
        'hari_kategori',
        'durasi_per_jam',
        'jam_masuk',
        'jam_pulang',
        'keterangan',
    ];
}
