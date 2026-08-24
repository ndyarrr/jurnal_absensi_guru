<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanJamSekolah extends Model
{
    protected $table = 'pengaturan_jam_sekolah';

    protected $fillable = [
        'hari_kategori',
        'durasi_per_jam',
        'mode_durasi_kbm',
        'durasi_jam_utama',
        'sampai_jam_ke',
        'durasi_jam_setelahnya',
        'jam_masuk',
        'jam_pulang',
        'durasi_istirahat_1',
        'setelah_jam_ke_1',
        'mode_istirahat_1',
        'jam_mulai_istirahat_1',
        'jam_selesai_istirahat_1',
        'durasi_istirahat_2',
        'setelah_jam_ke_2',
        'mode_istirahat_2',
        'jam_mulai_istirahat_2',
        'jam_selesai_istirahat_2',
        'keterangan',
    ];
}
