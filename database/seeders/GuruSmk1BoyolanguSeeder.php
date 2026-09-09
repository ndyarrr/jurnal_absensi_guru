<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Guru;
use App\Models\User;
use App\Models\Kelas;

class GuruSmk1BoyolanguSeeder extends Seeder
{
    /**
     * Seed 128 Teachers of SMKN 1 Boyolangu 2026/2027.
     */
    public function run(): void
    {
        $guruList = [
            ['nip' => '19810115 200312 1 003', 'nama_guru' => 'Trisno Wibowo, S.Pd., M.M'],
            ['nip' => '19670604 198903 2 009', 'nama_guru' => 'Martiin, S.Pd'],
            ['nip' => '19661207 199412 1 003', 'nama_guru' => 'Yani, S.Pd.'],
            ['nip' => '19700825 199512 2 001', 'nama_guru' => 'Siti Umiharsih, S.Pd'],
            ['nip' => '19700325 200312 2 007', 'nama_guru' => 'Winarsih, S.Pd, M.Pd'],
            ['nip' => '19701017 199703 2 004', 'nama_guru' => 'Dwi Rini Manfaati, S.Pd'],
            ['nip' => '19681128 200501 2 004', 'nama_guru' => 'Dra. Anik Indriani'],
            ['nip' => '19700304 200501 2 006', 'nama_guru' => 'Sri Rahayu, S.Pd'],
            ['nip' => '19750304 200604 2 017', 'nama_guru' => 'Arvia Rienatasary, S.Pd'],
            ['nip' => '19730601 200604 2 024', 'nama_guru' => 'Peni Wulandari, S.Pd'],
            ['nip' => '19691006 200701 2 022', 'nama_guru' => 'Rindang Rejeki, S.Pd'],
            ['nip' => '19710520 200604 2 018', 'nama_guru' => 'Erna Rinawati, S.Pd'],
            ['nip' => '19730108 200604 2 015', 'nama_guru' => 'Sunarti, S.Pd'],
            ['nip' => '19721030 200312 1 002', 'nama_guru' => 'Setiyo Winarko, S.Pd'],
            ['nip' => '19780202 200604 2 027', 'nama_guru' => 'Isti Mufadah, S.Pd'],
            ['nip' => '19731001 200604 2 012', 'nama_guru' => 'Indayah, S.Pd., M.Pd'],
            ['nip' => '19690425 200701 2 025', 'nama_guru' => 'Umi Kulsum, S.Pd'],
            ['nip' => '19691126 200701 2 007', 'nama_guru' => 'Rulik Indrawati, S.Pd'],
            ['nip' => '19690814 200701 2 026', 'nama_guru' => 'Lilik Suratmi, S.Pd'],
            ['nip' => '19670421 200701 1 026', 'nama_guru' => 'Basuki Sarjono, S.Pd'],
            ['nip' => '19680825 200801 2 019', 'nama_guru' => 'Titik Samsistini, S.Pd'],
            ['nip' => '19760210 200801 2 017', 'nama_guru' => 'Endang Ary Handayani, S.T., M.Pd'],
            ['nip' => '19690616 200701 2 026', 'nama_guru' => 'Purwati, S.Pd'],
            ['nip' => '19750409 200701 2 010', 'nama_guru' => 'Ninik Sriwidayati, S.Pd., M.Pd'],
            ['nip' => '19770817 200701 2 012', 'nama_guru' => 'Agustina Mardika Rini, S.Pd., M.Pd'],
            ['nip' => '19690805 200801 2 025', 'nama_guru' => 'Komariyah, S.Pd'],
            ['nip' => '19710806 200801 2 012', 'nama_guru' => 'Muashofah, M.Pd'],
            ['nip' => '19690915 200801 2 028', 'nama_guru' => 'Atih Wilupi, S.E, M.Pd'],
            ['nip' => '19801224 200801 2 016', 'nama_guru' => 'Winartin, S.Pd'],
            ['nip' => '19681014 200801 2 011', 'nama_guru' => 'Siti Khoiriyah, S.Pd'],
            ['nip' => '19690917 200701 2 012', 'nama_guru' => 'Sri Subekti, S.Pd'],
            ['nip' => '19700831 200801 2 017', 'nama_guru' => 'Kasmi, S.Pd., M.Pd'],
            ['nip' => '19700824 200801 1 008', 'nama_guru' => 'Ilham Sungeidi, S.Pd'],
            ['nip' => '19800329 200901 2 006', 'nama_guru' => 'Lutfia Marsalina, S.Pd.I, M.Pd.'],
            ['nip' => '19850910 200903 2 009', 'nama_guru' => 'Indriati, S.Pd'],
            ['nip' => '19761118 200701 1 004', 'nama_guru' => 'Agus Fahruddy, S.Pd., M.Pd'],
            ['nip' => '19790202 200701 2 025', 'nama_guru' => 'Titin Sukmasari, S.Pd., M.Pd'],
            ['nip' => '19800410 200901 2 007', 'nama_guru' => 'Dian Mawarti, S.Pd'],
            ['nip' => '19820303 200901 2 009', 'nama_guru' => 'Niken Hari Pratiwi, S.Psi., M.Pd'],
            ['nip' => '19740914 200901 2 001', 'nama_guru' => 'Siti Munawaroh, S.Kom., M.Pd'],
            ['nip' => '19821103 201001 2 025', 'nama_guru' => 'Dwi Nova Setyandari, S.Pd'],
            ['nip' => '19801026 201001 2 016', 'nama_guru' => 'Diana Hartanti, S.T., M.Pd'],
            ['nip' => '19730719 201001 2 002', 'nama_guru' => 'Andri Retno Yuli Astuti, S.Pd'],
            ['nip' => '19770426 201001 2 008', 'nama_guru' => 'Siswanti Purwaningsih, S.T., M.Pd'],
            ['nip' => '19760826 201001 2 010', 'nama_guru' => 'Ayu Puspitorini, ST'],
            ['nip' => '19800723 201001 2 016', 'nama_guru' => 'Elysa Yuli Nur\'aini, S.Si'],
            ['nip' => '19710826 200604 1 011', 'nama_guru' => 'Agus Muharyanto, M.Pd'],
            ['nip' => '19781001 200604 2 021', 'nama_guru' => 'Septiani, S.Pd., M.Pd'],
            ['nip' => '19870316 200901 2 002', 'nama_guru' => 'Retno Widyastuti, S.Pd., M.Pd'],
            ['nip' => '19840222 200902 2 007', 'nama_guru' => 'Ratih Dian Irawati, SE'],
            ['nip' => '19830101 201001 1 042', 'nama_guru' => 'Andri Krisdianto, SE., M.Pd'],
            ['nip' => '19850418 201001 2 031', 'nama_guru' => 'Ruly Dwi Setyaningrum, S.Kom'],
            ['nip' => '19770306 201101 1 003', 'nama_guru' => 'Ary Sunaryo, ST., M.Pd'],
            ['nip' => '19820204 201101 2 006', 'nama_guru' => 'Listyana Hartati, S.Kom., M.Pd'],
            ['nip' => '19870217 201101 2 012', 'nama_guru' => 'Dhuana Putri Puspetasary, S.Pd'],
            ['nip' => '19860127 201101 1 013', 'nama_guru' => 'Angga Widhy Wirawan, S.Pd., M.Pd'],
            ['nip' => '19830113 200901 1 003', 'nama_guru' => 'Mas\'an Widodo, S.Pd. M.T'],
            ['nip' => '19850203 201101 1 012', 'nama_guru' => 'Endik Kuswantoro, S.Kom., M.T'],
            ['nip' => '19711129 201101 1 002', 'nama_guru' => 'Anang Prasetyo, S.Pd'],
            ['nip' => '19780830 200701 1 017', 'nama_guru' => 'Arif Setyobudi, S.Pd'],
            ['nip' => '19760719 200901 1 003', 'nama_guru' => 'Benny Mamora, S.Kom'],
            ['nip' => '19850316 201101 1 012', 'nama_guru' => 'Danang Anjar Hymawanto, S.Pd'],
            ['nip' => '19820822 201407 2 002', 'nama_guru' => 'Hardini Indahing Budi, S.E., M.Pd.'],
            ['nip' => '19900907 201903 1 004', 'nama_guru' => 'Erwan Septiyono, S.Pd'],
            ['nip' => '19910708 201903 2 017', 'nama_guru' => 'Istiana Suhartati, S.T'],
            ['nip' => '19960728 202012 2 013', 'nama_guru' => 'Risqi Nur Imama, S.Tr.Par'],
            ['nip' => '19900418 202012 1 017', 'nama_guru' => 'Badrus Sulaiman, S.Pd.'],
            ['nip' => '19780822 202221 2 006', 'nama_guru' => 'Nurul Azizah, S.Pd'],
            ['nip' => '19771112 202221 1 007', 'nama_guru' => 'Hendro Suwignyo, ST'],
            ['nip' => '19740805 202221 2 008', 'nama_guru' => 'Dyah Esti Rahayu, S.Pd'],
            ['nip' => '19810124 202221 1 012', 'nama_guru' => 'Baskoro, S.Si'],
            ['nip' => '19830306 202221 2 048', 'nama_guru' => 'Luluk Munfarida, S.Pd'],
            ['nip' => '19830707 202221 2 027', 'nama_guru' => 'Khuriyatul Kamila, S.Si'],
            ['nip' => '19880521 202221 2 020', 'nama_guru' => 'Veronica Damay Rulitasari, S.Pd'],
            ['nip' => '19870303 202221 2 026', 'nama_guru' => 'Nur Nastutisari, S.ST.Par.'],
            ['nip' => '19820318 202221 1 012', 'nama_guru' => 'Alfinu Farikh Abdillah, S.Pd.I'],
            ['nip' => '19910914 202221 2 015', 'nama_guru' => 'Khoyrotun Hisani, S.Sn'],
            ['nip' => '19911103 202221 1 007', 'nama_guru' => 'Joko Priyanto, S.Kom'],
            ['nip' => '19920504 202221 2 022', 'nama_guru' => 'Elyana Frisca Monica, S.Pd'],
            ['nip' => '19951027 202221 2 012', 'nama_guru' => 'Rika Okta Maulida, S.Ds.'],
            ['nip' => '19670512 202221 2 003', 'nama_guru' => 'Dra. Hanik Pangestuti'],
            ['nip' => '19850112 202221 1 020', 'nama_guru' => 'Sa\'ad Wasis Hiedayat, S.Pd'],
            ['nip' => '19850121 202221 2 037', 'nama_guru' => 'Shinta Indyar Shanty Susanto, S.Kom'],
            ['nip' => '19871014 202221 1 014', 'nama_guru' => 'Widodo, S.Pd'],
            ['nip' => '19940101 202221 2 024', 'nama_guru' => 'Nur Eko Wahyuningsih, S.Pd'],
            ['nip' => '19970318 202221 2 010', 'nama_guru' => 'Kurnila Putri Islamawati, S.Pd'],
            ['nip' => '19710728 202321 2 004', 'nama_guru' => 'Sulistyowati, SS'],
            ['nip' => '19750616 202321 2 007', 'nama_guru' => 'Wiwik Yuniarsih, S.Pd'],
            ['nip' => '19780810 202321 1 005', 'nama_guru' => 'Fajar Luthfianto, S.Pd'],
            ['nip' => '19820529 202321 2 015', 'nama_guru' => 'Fajar Wahyu Pratiwi, S.S'],
            ['nip' => '19820718 202321 1 006', 'nama_guru' => 'Agung Yulianto, S.Pd'],
            ['nip' => '19830331 202321 2 015', 'nama_guru' => 'Sri Kusumastuti, S.Pd'],
            ['nip' => '19840730 202321 2 018', 'nama_guru' => 'Yuli Ratnasari, S.Pd'],
            ['nip' => '19850627 202321 2 020', 'nama_guru' => 'Fitria Renytasari, S.Pd'],
            ['nip' => '19710523 202421 2 002', 'nama_guru' => 'Tutut Sriatin, S.Pd'],
            ['nip' => '19751113 202421 1 001', 'nama_guru' => 'Dwi Kuswanto, S.Pd'],
            ['nip' => '19751211 202421 2 008', 'nama_guru' => 'Erna Qoriah, S.E.'],
            ['nip' => '19780701 202421 2 002', 'nama_guru' => 'Pipit Ambarwati, S.Pd'],
            ['nip' => '19800312 202421 2 013', 'nama_guru' => 'Fitri Amaliyah, S.Pd'],
            ['nip' => '19880113 202421 2 002', 'nama_guru' => 'Niken Dewi Hastika, S.Pd'],
            ['nip' => '19880503 202421 2 030', 'nama_guru' => 'Ista Nofasari, S.Pd'],
            ['nip' => '19880521 202421 2 009', 'nama_guru' => 'Anisa Kusumawati, S.Pd'],
            ['nip' => '19920423 202421 2 010', 'nama_guru' => 'Mega Mahardika, S.Pd'],
            ['nip' => '19940415 202421 2 053', 'nama_guru' => 'Rifkotin Na\'imah, S.Pd'],
            ['nip' => '19901031 202521 2 015', 'nama_guru' => 'Sinta Lestari, S.Pd.I'],
            ['nip' => '19900806 202521 2 028', 'nama_guru' => 'Astra Bella Flamboyan, S.Psi'],
            ['nip' => '19970204 202521 2 014', 'nama_guru' => 'Fitria Diah Ayu Hartati, S.Pd'],
            ['nip' => '19661108 202521 2 001', 'nama_guru' => 'Dra. Susakti Yuharini'],
            ['nip' => '19700717 202521 1 036', 'nama_guru' => 'Agus Pramono, S.Sn'],
            ['nip' => '19871004 202521 2 098', 'nama_guru' => 'Ajeng Okvitasari, S.Pd'],
            ['nip' => '19860414 202521 2 103', 'nama_guru' => 'Nishfu Laili, S.Pd'],
            ['nip' => '19830102 202521 1 100', 'nama_guru' => 'Andika Christian Sasmita, S.ST'],
            ['nip' => '19821215 202521 2 065', 'nama_guru' => 'Muto\'atul Khosi\'ah, S.Pd'],
            ['nip' => '19871213 202521 1 096', 'nama_guru' => 'Bella Prakoso, S.Pd'],
            ['nip' => '19920205 202521 2 129', 'nama_guru' => 'Yustin Febrini, S.Pd'],
            ['nip' => '19850926 202521 2 055', 'nama_guru' => 'Siti Maisaroh, S.Pd'],
            ['nip' => '19890418 202521 2 117', 'nama_guru' => 'Laili Ermawati, S.Pd'],
            ['nip' => '19971223 202521 1 078', 'nama_guru' => 'Muhammad Fajar Assidiqi, S.Pd'],
            ['nip' => '19820609 202521 2 057', 'nama_guru' => 'Yuni Jiastuti, S.Pd'],
            ['nip' => '19781006 202521 1 046', 'nama_guru' => 'Tuhu Eries Kudori, S.Sn'],
            ['nip' => '19961117 202521 1 096', 'nama_guru' => 'Eko Saputro, S.Pd'],
            ['nip' => '19871116 202521 1 085', 'nama_guru' => 'Zainul Arifin, S.Pd'],
            ['nip' => '-', 'nama_guru' => 'Endang Safitri, S.Pd'],
            ['nip' => '-', 'nama_guru' => 'Mufatiroh, S.Ag'],
            ['nip' => '-', 'nama_guru' => 'Abdul Rohman, S.Pd'],
            ['nip' => '-', 'nama_guru' => 'Rizki Putri Wulandari, S.Pd'],
            ['nip' => '-', 'nama_guru' => 'Pdt. Juklianus Steven Immanuel Bahihi, S.Pdk., M.Pd'],
            ['nip' => '-', 'nama_guru' => 'Sukamto, S.Ag'],
        ];

        // Disable foreign key checks for clean truncation and insertion
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        if (Schema::hasTable('guru_mapel')) {
            DB::table('guru_mapel')->truncate();
        }

        // Clear old guru records completely (force delete soft-deleted as well)
        Guru::withTrashed()->forceDelete();

        // Insert 128 new official teachers
        $insertedMap = [];
        $firstGuruId = null;
        foreach ($guruList as $idx => $gData) {
            $cleanNuptk = str_replace(' ', '', $gData['nip']);
            if ($cleanNuptk === '-' || empty($cleanNuptk)) {
                $cleanNuptk = 'HONORER' . str_pad((string)($idx + 1), 3, '0', STR_PAD_LEFT);
            }
            $guru = Guru::create([
                'nip' => $cleanNuptk,
                'nama_guru' => $gData['nama_guru'],
                'no_hp' => '08' . str_pad((string)($idx + 1), 10, '0', STR_PAD_LEFT),
            ]);
            if ($firstGuruId === null) {
                $firstGuruId = $guru->id_guru;
            }
            $insertedMap[$gData['nama_guru']] = $guru;
        }

        // Re-link tables with NOT NULL FK constraints to valid new teacher IDs
        if (Schema::hasTable('jadwal_pelajaran') && $firstGuruId) {
            DB::table('jadwal_pelajaran')->whereNotIn('id_guru', array_map(fn($g) => $g->id_guru, $insertedMap))->update(['id_guru' => $firstGuruId]);
        }
        if (Schema::hasTable('kelas') && $firstGuruId) {
            DB::table('kelas')->whereNotNull('id_guru_wali')->whereNotIn('id_guru_wali', array_map(fn($g) => $g->id_guru, $insertedMap))->update(['id_guru_wali' => $firstGuruId]);
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 4. Re-link users individually to unique teacher records
        $allUsers = User::all();
        $usedGuruIds = [];

        foreach ($allUsers as $u) {
            $targetGuru = null;
            if ($u->role === 'kepala_sekolah' && isset($insertedMap['Trisno Wibowo, S.Pd., M.M'])) {
                $targetGuru = $insertedMap['Trisno Wibowo, S.Pd., M.M'];
            } elseif ($u->role === 'waka' && isset($insertedMap['Hardini Indahing Budi, S.E., M.Pd.'])) {
                $targetGuru = $insertedMap['Hardini Indahing Budi, S.E., M.Pd.'];
            } elseif ($u->role === 'waka_sdm' && isset($insertedMap['Setiyo Winarko, S.Pd'])) {
                $targetGuru = $insertedMap['Setiyo Winarko, S.Pd'];
            } elseif ($u->role === 'guru_piket' && isset($insertedMap['Baskoro, S.Si'])) {
                $targetGuru = $insertedMap['Baskoro, S.Si'];
            } elseif ($u->role === 'wali_kelas' && isset($insertedMap['Kurnila Putri Islamawati, S.Pd'])) {
                $targetGuru = $insertedMap['Kurnila Putri Islamawati, S.Pd'];
            }

            if ($targetGuru && !in_array($targetGuru->id_guru, $usedGuruIds, true)) {
                $u->update(['id_guru' => $targetGuru->id_guru]);
                $usedGuruIds[] = $targetGuru->id_guru;
            } else {
                // Find next unused teacher for this user
                foreach ($insertedMap as $gObj) {
                    if (!in_array($gObj->id_guru, $usedGuruIds, true)) {
                        $u->update(['id_guru' => $gObj->id_guru]);
                        $usedGuruIds[] = $gObj->id_guru;
                        break;
                    }
                }
            }
        }
    }
}
