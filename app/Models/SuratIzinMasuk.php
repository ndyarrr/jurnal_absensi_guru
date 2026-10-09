<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratIzinMasuk extends Model
{
    use SoftDeletes;

    protected $table = 'surat_izin_masuk';
    protected $primaryKey = 'id_surat_izin_masuk';

    protected $fillable = [
        'nomor_surat',
        'id_siswa',
        'id_kelas',
        'nama_siswa',
        'kelas_str',
        'jam_pelajaran_ke',
        'alasan',
        'jenis_surat',
        'tanggal',
        'nama_piket_wakasek',
        'nama_guru_piket',
        'id_user_piket',
        'ttd_siswa_path',
        'ttd_siswa_signed_at',
        'ttd_siswa_signed_name',
        'ttd_guru_piket_path',
        'ttd_guru_piket_signed_at',
        'ttd_guru_piket_signed_name',
        'ttd_wakasek_path',
        'ttd_wakasek_signed_at',
        'ttd_wakasek_signed_name',
        'ttd_wakasek_id_user',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'ttd_siswa_signed_at' => 'datetime',
        'ttd_guru_piket_signed_at' => 'datetime',
        'ttd_wakasek_signed_at' => 'datetime',
    ];

    /** Peran penanda tangan: key URL => kolom dasar di database. */
    public const PERAN_TTD = [
        'siswa' => 'ttd_siswa',
        'guru-piket' => 'ttd_guru_piket',
        'wakasek' => 'ttd_wakasek',
    ];

    public function ttdUrl(string $peran): ?string
    {
        $path = $this->{self::PERAN_TTD[$peran] . '_path'} ?? null;

        return $path ? asset('storage/' . ltrim($path, '/')) : null;
    }

    public function sudahTtd(string $peran): bool
    {
        return !empty($this->{self::PERAN_TTD[$peran] . '_path'});
    }

    /** Surat boleh dicetak hanya jika ketiga pihak sudah tanda tangan digital. */
    public function ttdLengkap(): bool
    {
        foreach (array_keys(self::PERAN_TTD) as $peran) {
            if (!$this->sudahTtd($peran)) {
                return false;
            }
        }

        return true;
    }

    public function jumlahTtd(): int
    {
        return collect(array_keys(self::PERAN_TTD))
            ->filter(fn ($peran) => $this->sudahTtd($peran))
            ->count();
    }

    /** id_guru yang terjadwal sebagai Piket Waka pada tanggal surat. */
    public function idGuruPiketWaka(): array
    {
        return JadwalPiket::berlakuPada($this->tanggal ?? \Carbon\Carbon::now('Asia/Jakarta'))
            ->where('peran', 'Piket Waka')
            ->pluck('id_guru')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * Apakah $user berhak menandatangani slot $peran pada surat ini.
     *  - siswa      : dilakukan siswa di perangkat guru piket (akun piket yang sedang login)
     *  - guru-piket : hanya guru piket yang menerbitkan surat
     *  - wakasek    : hanya akun Piket Waka yang terjadwal hari itu (bukan guru piket yang menerbitkan)
     * Tanda tangan hanya bisa diberikan pada hari surat diterbitkan dan tidak bisa ditimpa.
     */
    public function bolehDitandatangani($user, string $peran): bool
    {
        if (!$user || !isset(self::PERAN_TTD[$peran]) || $this->sudahTtd($peran)) {
            return false;
        }

        if (!$this->tanggal || !$this->tanggal->isSameDay(\Carbon\Carbon::now('Asia/Jakarta'))) {
            return false;
        }

        return match ($peran) {
            'siswa' => true,
            'guru-piket' => (int) $user->id === (int) $this->id_user_piket,
            'wakasek' => (int) $user->id !== (int) $this->id_user_piket
                && $user->resolveIdGuru() !== null
                && in_array((int) $user->resolveIdGuru(), $this->idGuruPiketWaka(), true),
        };
    }

    /**
     * Rentang jam dari kolom jam_pelajaran_ke ("ke- 2" atau "ke- 2 s/d 4").
     * Return [mulai, selesai]; selesai null = Izin Masuk Kelas (siswa baru datang di jam mulai).
     */
    public function rentangJam(): ?array
    {
        if (!preg_match_all('/\d+/', (string) $this->jam_pelajaran_ke, $m) || empty($m[0])) {
            return null;
        }

        return [(int) $m[0][0], isset($m[0][1]) ? (int) $m[0][1] : null];
    }

    /**
     * Status absensi otomatis untuk jam pelajaran ke-$jamKe pada tanggal surat.
     *  - Izin Masuk Kelas (jam ke-N)       : jam sebelum N dihitung Alpa (terlambat).
     *  - Meninggalkan Kelas (jam ke-A s/d B): jam A sampai B dihitung Sakit jika alasan mengandung
     *    kata "sakit", selain itu Dispen Keluar.
     * Return null jika surat ini tidak mengubah status di jam tersebut (siswa dianggap hadir).
     */
    public function statusUntukJam(int $jamKe): ?array
    {
        $rentang = $this->rentangJam();
        if (!$rentang) {
            return null;
        }

        [$mulai, $selesai] = $rentang;

        if ($selesai === null) {
            if ($jamKe < $mulai) {
                return [
                    'status' => 'Alpa',
                    'keterangan' => \Illuminate\Support\Str::limit("[Terlambat] Masuk kelas jam ke-{$mulai} (Surat Ijin Masuk {$this->nomor_surat})", 250, ''),
                ];
            }

            return null;
        }

        if ($jamKe >= $mulai && $jamKe <= $selesai) {
            $karenaSakit = stripos((string) $this->alasan, 'sakit') !== false;

            return [
                'status' => $karenaSakit ? 'Sakit' : 'Dispen Keluar',
                'keterangan' => \Illuminate\Support\Str::limit("[Meninggalkan kelas jam ke-{$mulai} s/d {$selesai}] {$this->alasan} ({$this->nomor_surat})", 250, ''),
            ];
        }

        return null;
    }

    /**
     * Status otomatis per siswa untuk satu jam pelajaran pada $tanggal.
     * Return [id_siswa => ['status' => ..., 'keterangan' => ..., 'nomor_surat' => ...]] (hanya siswa yang terdampak).
     */
    public static function autoPerSiswa($siswaIds, $tanggal, int $jamKe): array
    {
        $hasil = [];

        $daftar = static::whereIn('id_siswa', collect($siswaIds)->all())
            ->whereDate('tanggal', $tanggal)
            ->orderBy('id_surat_izin_masuk')
            ->get();

        foreach ($daftar as $surat) {
            $auto = $surat->statusUntukJam($jamKe);
            if ($auto && !isset($hasil[$surat->id_siswa])) {
                $hasil[$surat->id_siswa] = $auto + [
                    'nomor_surat' => $surat->nomor_surat,
                    'detail' => $surat->detailUntukModal(),
                ];
            }
        }

        return $hasil;
    }

    /** Data ringkas surat untuk modal "Lihat Surat" di form jurnal guru mapel. */
    public function detailUntukModal(): array
    {
        $rentang = $this->rentangJam();
        $meninggalkan = $rentang && $rentang[1] !== null;

        return [
            'type' => 'izin_masuk',
            'status_label' => 'Surat Ijin Masuk / Meninggalkan Kelas (Guru Piket)',
            'nomor' => $this->nomor_surat,
            'jenis' => $meninggalkan ? 'Meninggalkan Kelas' : 'Izin Masuk Kelas',
            'jam' => $rentang
                ? ($meninggalkan ? "Jam ke-{$rentang[0]} s/d {$rentang[1]}" : "Masuk kelas jam ke-{$rentang[0]}")
                : (string) $this->jam_pelajaran_ke,
            'tanggal' => $this->tanggal ? $this->tanggal->format('d/m/Y') : '-',
            'alasan' => $this->alasan ?: '-',
            'ttd' => [
                ['label' => 'Siswa', 'nama' => $this->nama_siswa, 'url' => $this->ttdUrl('siswa')],
                ['label' => 'Guru Piket', 'nama' => $this->nama_guru_piket, 'url' => $this->ttdUrl('guru-piket')],
                ['label' => 'Piket Wakasek', 'nama' => $this->nama_piket_wakasek, 'url' => $this->ttdUrl('wakasek')],
            ],
        ];
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa')->withTrashed();
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas')->withTrashed();
    }
}