<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\JamPelajaran;
use App\Models\JadwalPelajaran;
use App\Models\JurnalMengajar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with complete realistic master data.
     */
    public function run(): void
    {
        // 1. Seed Jam Pelajaran
        $jamList = [
            ['jam_ke' => 1, 'jam_mulai' => '07:00:00', 'jam_selesai' => '07:45:00', 'keterangan' => 'Jam Ke-1'],
            ['jam_ke' => 2, 'jam_mulai' => '07:45:00', 'jam_selesai' => '08:30:00', 'keterangan' => 'Jam Ke-2'],
            ['jam_ke' => 3, 'jam_mulai' => '08:30:00', 'jam_selesai' => '09:15:00', 'keterangan' => 'Jam Ke-3'],
            ['jam_ke' => 4, 'jam_mulai' => '09:30:00', 'jam_selesai' => '10:15:00', 'keterangan' => 'Jam Ke-4'],
            ['jam_ke' => 5, 'jam_mulai' => '10:15:00', 'jam_selesai' => '11:00:00', 'keterangan' => 'Jam Ke-5'],
            ['jam_ke' => 6, 'jam_mulai' => '11:00:00', 'jam_selesai' => '11:45:00', 'keterangan' => 'Jam Ke-6'],
        ];

        foreach ($jamList as $j) {
            JamPelajaran::updateOrCreate(['jam_ke' => $j['jam_ke']], $j);
        }

        // 2. Seed Guru
        $guruData = [
            ['nuptk' => '198501152010011001', 'nama_guru' => 'Trisno Wibowo, S.Pd., M.M.', 'no_hp' => '081234567801'],
            ['nuptk' => '199002202015022002', 'nama_guru' => 'Kurnila Putri Islamawati, S.Pd', 'no_hp' => '081234567802'],
            ['nuptk' => '198803102012011003', 'nama_guru' => 'Budi Santoso, S.Kom', 'no_hp' => '081234567803'],
            ['nuptk' => '199204052018022004', 'nama_guru' => 'Rina Amelia, S.Pd', 'no_hp' => '081234567804'],
            ['nuptk' => '198605122011011005', 'nama_guru' => 'Agus Prasetyo, S.T', 'no_hp' => '081234567805'],
            ['nuptk' => '199106182017022006', 'nama_guru' => 'Dewi Lestari, S.Pd', 'no_hp' => '081234567806'],
            ['nuptk' => '198907252014011007', 'nama_guru' => 'Hendra Wijaya, S.Kom', 'no_hp' => '081234567807'],
            ['nuptk' => '199308302019022008', 'nama_guru' => 'Siti Nurhaliza, S.Pd', 'no_hp' => '081234567808'],
            ['nuptk' => '199409142020022009', 'nama_guru' => 'Anisa Kusumawati, S.Pd', 'no_hp' => '081234567809'],
        ];

        $gurus = [];
        foreach ($guruData as $g) {
            $gurus[] = Guru::updateOrCreate(['nuptk' => $g['nuptk']], $g);
        }

        // 3. Seed Users with Roles & Link to Guru
        $usersData = [
            [
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'id_guru' => null,
            ],
            [
                'name' => 'Trisno Wibowo (Guru)',
                'email' => 'trisno@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'guru_mengajar',
                'id_guru' => $gurus[0]->id_guru,
            ],
            [
                'name' => 'Kurnila (Wali Kelas)',
                'email' => 'kurnila@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'wali_kelas',
                'id_guru' => $gurus[1]->id_guru,
            ],
            [
                'name' => 'Budi Santoso (Guru Piket)',
                'email' => 'budi@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'guru_piket',
                'id_guru' => $gurus[2]->id_guru,
            ],
            [
                'name' => 'Kepala Sekolah',
                'email' => 'kepsek@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'kepala_sekolah',
                'id_guru' => $gurus[3]->id_guru,
            ],
            [
                'name' => 'Waka SDM',
                'email' => 'wakasdm@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'waka_sdm',
                'id_guru' => $gurus[4]->id_guru,
            ],
            [
                'name' => 'Satpam Gerbang',
                'email' => 'satpam@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'satpam',
                'id_guru' => null,
            ],
        ];

        foreach ($usersData as $u) {
            User::updateOrCreate(['email' => $u['email']], $u);
        }

        // 4. Seed Jurusan
        $jurusanData = [
            ['kode_jurusan' => 'RPL', 'nama_jurusan' => 'Rekayasa Perangkat Lunak'],
            ['kode_jurusan' => 'DKV', 'nama_jurusan' => 'Desain Komunikasi Visual'],
            ['kode_jurusan' => 'ANIM', 'nama_jurusan' => 'Animasi'],
            ['kode_jurusan' => 'KLN', 'nama_jurusan' => 'Kuliner'],
        ];
        $jurusans = [];
        foreach ($jurusanData as $jData) {
            $jurusans[] = Jurusan::firstOrCreate(['kode_jurusan' => $jData['kode_jurusan']], $jData);
        }

        // 5. Seed Kelas
        $kelasData = [
            ['tingkat' => 'XI', 'id_jurusan' => $jurusans[0]->id_jurusan, 'rombel' => 1, 'id_guru_wali' => $gurus[1]->id_guru, 'wali_kelas' => 'Kurnila Putri Islamawati, S.Pd', 'jumlah_siswa' => 32],
            ['tingkat' => 'X',  'id_jurusan' => $jurusans[0]->id_jurusan, 'rombel' => 1, 'id_guru_wali' => $gurus[3]->id_guru, 'wali_kelas' => 'Rina Amelia, S.Pd', 'jumlah_siswa' => 30],
            ['tingkat' => 'XI', 'id_jurusan' => $jurusans[1]->id_jurusan, 'rombel' => 1, 'id_guru_wali' => $gurus[2]->id_guru, 'wali_kelas' => 'Budi Santoso, S.Kom', 'jumlah_siswa' => 34],
            ['tingkat' => 'X',  'id_jurusan' => $jurusans[2]->id_jurusan, 'rombel' => 1, 'id_guru_wali' => $gurus[5]->id_guru, 'wali_kelas' => 'Dewi Lestari, S.Pd', 'jumlah_siswa' => 28],
            ['tingkat' => 'X',  'id_jurusan' => $jurusans[3]->id_jurusan, 'rombel' => 2, 'id_guru_wali' => $gurus[4]->id_guru, 'wali_kelas' => 'Agus Prasetyo, S.T', 'jumlah_siswa' => 31],
        ];

        $kelases = [];
        foreach ($kelasData as $k) {
            $kelases[] = Kelas::firstOrCreate([
                'tingkat' => $k['tingkat'],
                'id_jurusan' => $k['id_jurusan'],
                'rombel' => $k['rombel']
            ], $k);
        }

        // 6. Seed Mapel
        $mapelNames = ['Informatika', 'Konsentrasi RPL', 'Bahasa Inggris', 'Bahasa Jawa', 'Seni Budaya', 'Bahasa Jepang', 'IPAS', 'PPKN'];
        $mapels = [];
        foreach ($mapelNames as $mName) {
            $mapels[] = Mapel::firstOrCreate(['nama_mapel' => $mName]);
        }

        // 7. Seed Siswa (Sample Students)
        $siswaNames = [
            'Ahmad Fauzi', 'Bagus Pratama', 'Citra Kirana', 'Dinda Permata', 'Eko Prasetyo',
            'Fani Rahmawati', 'Gilang Ramadhan', 'Hany Saputri', 'Indra Wijaya', 'Jasmine Aulia'
        ];

        foreach ($siswaNames as $idx => $sName) {
            Siswa::firstOrCreate([
                'nisn' => '0056789' . str_pad($idx + 1, 3, '0', STR_PAD_LEFT)
            ], [
                'nama_siswa' => $sName,
                'id_kelas' => $kelases[$idx % count($kelases)]->id_kelas,
            ]);
        }

        // 8. Seed Jadwal Pelajaran
        $jadwalData = [
            ['id_kelas' => $kelases[0]->id_kelas, 'hari' => 'Senin', 'jam_ke' => 1, 'id_jam' => 1, 'id_guru' => $gurus[0]->id_guru, 'id_mapel' => $mapels[1]->id_mapel],
            ['id_kelas' => $kelases[1]->id_kelas, 'hari' => 'Senin', 'jam_ke' => 2, 'id_jam' => 2, 'id_guru' => $gurus[1]->id_guru, 'id_mapel' => $mapels[0]->id_mapel],
            ['id_kelas' => $kelases[2]->id_kelas, 'hari' => 'Senin', 'jam_ke' => 3, 'id_jam' => 3, 'id_guru' => $gurus[2]->id_guru, 'id_mapel' => $mapels[2]->id_mapel],
            ['id_kelas' => $kelases[3]->id_kelas, 'hari' => 'Senin', 'jam_ke' => 4, 'id_jam' => 4, 'id_guru' => $gurus[3]->id_guru, 'id_mapel' => $mapels[3]->id_mapel],
            ['id_kelas' => $kelases[4]->id_kelas, 'hari' => 'Senin', 'jam_ke' => 5, 'id_jam' => 5, 'id_guru' => $gurus[4]->id_guru, 'id_mapel' => $mapels[4]->id_mapel],
        ];

        $jadwals = [];
        foreach ($jadwalData as $jad) {
            $jadwals[] = JadwalPelajaran::firstOrCreate([
                'id_kelas' => $jad['id_kelas'],
                'hari' => $jad['hari'],
                'jam_ke' => $jad['jam_ke']
            ], $jad);
        }

        // 9. Seed Jurnal Mengajar (Recent Activity)
        $today = now()->toDateString();
        $jurnalEntries = [
            ['id_jadwal' => $jadwals[0]->id_jadwal, 'tanggal' => $today, 'status_kehadiran' => 'Hadir', 'materi' => 'Implementasi Clean Architecture Laravel', 'jumlah_hadir' => 32, 'jumlah_tidak_hadir' => 0, 'catatan' => 'Siswa mengikuti praktikum dengan antusias.'],
            ['id_jadwal' => $jadwals[1]->id_jadwal, 'tanggal' => $today, 'status_kehadiran' => 'Hadir', 'materi' => 'Pengenalan Algoritma & Pemrograman', 'jumlah_hadir' => 30, 'jumlah_tidak_hadir' => 0, 'catatan' => 'Materi dipahami dengan baik.'],
            ['id_jadwal' => $jadwals[2]->id_jadwal, 'tanggal' => $today, 'status_kehadiran' => 'Hadir', 'materi' => 'Reading Comprehension & Technical Vocabulary', 'jumlah_hadir' => 33, 'jumlah_tidak_hadir' => 1, 'catatan' => '1 siswa izin sakit.'],
        ];

        foreach ($jurnalEntries as $jEntry) {
            JurnalMengajar::firstOrCreate([
                'id_jadwal' => $jEntry['id_jadwal'],
                'tanggal' => $jEntry['tanggal']
            ], $jEntry);
        }
    }
}
