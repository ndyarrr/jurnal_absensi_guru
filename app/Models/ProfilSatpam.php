<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilSatpam extends Model
{
    protected $table = 'profil_satpam';
    protected $primaryKey = 'id_profil_satpam';

    protected $fillable = [
        'id_user',
        'id_petugas',
        'email',
        'pos_jaga',
        'jadwal_shift',
        'notif_izin_belum_kembali',
        'notif_laporan_terkirim',
        'notif_pengumuman_sekolah',
    ];

    protected $casts = [
        'notif_izin_belum_kembali' => 'boolean',
        'notif_laporan_terkirim' => 'boolean',
        'notif_pengumuman_sekolah' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}