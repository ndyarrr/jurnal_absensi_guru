<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/JurnalMengajar.php
class JurnalMengajar extends Model
{
    protected $table = 'jurnal_mengajar';
    protected $primaryKey = 'id_jurnal';
    public $timestamps = false;
    protected $fillable = [
        'id_jadwal', 'tanggal', 'status_kehadiran', 'id_guru_pengganti',
        'materi', 'jumlah_hadir', 'jumlah_tidak_hadir', 'catatan'
    ];

    public function jadwal() { return $this->belongsTo(JadwalPelajaran::class, 'id_jadwal'); }
    public function guruPengganti() { return $this->belongsTo(Guru::class, 'id_guru_pengganti'); }
    public function detailKetidakhadiran() { return $this->hasMany(DetailKetidakhadiran::class, 'id_jurnal'); }
}