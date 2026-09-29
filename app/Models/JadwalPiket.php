<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPiket extends Model
{
    use HasFactory;

    protected $table = 'jadwal_piket';
    protected $primaryKey = 'id_piket';

    protected $fillable = [
        'hari',
        'tanggal',
        'id_guru',
        'peran',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /** Peran piket sesuai lembar sekolah (urutan tampil). key = nama peran, value = label jam. */
    public const PERAN = [
        'Petugas KBM Pagi'      => '07.00 - 11.00',
        'Koordinator KBM Pagi'  => '07.00 - 11.00',
        'Petugas KBM Siang'     => '11.00 - 15.00',
        'Koordinator KBM Siang' => '11.00 - 15.00',
        'Piket Waka'            => '',
    ];

    public const HARI_ID = [
        1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat',
        6 => 'Sabtu', 7 => 'Minggu',
    ];

    /** Cek sekali per request apakah kolom `tanggal` sudah ada. */
    public static function punyaKolomTanggal(): bool
    {
        static $ada = null;
        if ($ada === null) {
            $ada = \Illuminate\Support\Facades\Schema::hasTable('jadwal_piket')
                && \Illuminate\Support\Facades\Schema::hasColumn('jadwal_piket', 'tanggal');
        }
        return $ada;
    }

    public static function namaHari(Carbon $tanggal): string
    {
        return self::HARI_ID[$tanggal->dayOfWeekIso];
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru')->withTrashed();
    }

    /**
     * Baris yang berlaku pada tanggal tertentu:
     *  - baris per-tanggal yang cocok, ATAU
     *  - baris lama (tanggal NULL) yang harinya cocok (jadwal mingguan berulang).
     */
    public function scopeBerlakuPada($query, Carbon $tanggal)
    {
        $hari = self::namaHari($tanggal);
        $tgl = $tanggal->toDateString();

        // Migration belum dijalankan -> pakai jadwal mingguan lama.
        if (!self::punyaKolomTanggal()) {
            return $query->where('hari', $hari);
        }

        return $query->where(function ($q) use ($tgl, $hari) {
            $q->whereDate('tanggal', $tgl)
              ->orWhere(function ($q2) use ($hari) {
                  $q2->whereNull('tanggal')->where('hari', $hari);
              });
        });
    }

    /** Jadwal mingguan lama, atau jadwal per-tanggal dari hari ini ke depan. */
    public function scopeMasihBerlaku($query)
    {
        if (!self::punyaKolomTanggal()) {
            return $query;
        }

        $hariIni = Carbon::now('Asia/Jakarta')->toDateString();

        return $query->where(function ($q) use ($hariIni) {
            $q->whereNull('tanggal')->orWhereDate('tanggal', '>=', $hariIni);
        });
    }

    public function scopeBertugasHariIni($query)
    {
        return $query->berlakuPada(Carbon::now('Asia/Jakarta'));
    }
}