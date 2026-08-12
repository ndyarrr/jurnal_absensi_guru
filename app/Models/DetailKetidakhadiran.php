<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailKetidakhadiran extends Model
{
    protected $table = 'detail_ketidakhadiran';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;
    protected $fillable = [
        'id_jurnal',
        'id_siswa',
        'status',
        'kategori',
        'bukti_surat',
        'catatan',
        'id_guru_piket',
        'waktu_input',
    ];

    public function jurnal()
    {
        return $this->belongsTo(JurnalMengajar::class, 'id_jurnal');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function guruPiket()
    {
        return $this->belongsTo(Guru::class, 'id_guru_piket', 'id_guru');
    }
}