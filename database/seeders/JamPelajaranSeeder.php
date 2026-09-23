<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Controllers\JamPelajaranController;
use Illuminate\Http\Request;
use App\Models\JamPelajaran;

class JamPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JamPelajaran::query()->delete();
        $ctrl = new JamPelajaranController();

        $req1 = new Request([
            'hari_kategori'      => 'Senin-Kamis',
            'durasi_per_jam'     => 40,
            'jam_masuk'          => '07:00',
            'jam_pulang'         => '15:00',
            'durasi_istirahat_1' => 20,
            'setelah_jam_ke_1'   => 4,
            'durasi_istirahat_2' => 60,
            'setelah_jam_ke_2'   => 7,
        ]);
        $ctrl->generateSlots($req1);

        $req2 = new Request([
            'hari_kategori'      => 'Jumat',
            'durasi_per_jam'     => 30,
            'jam_masuk'          => '07:00',
            'jam_pulang'         => '15:30',
            'durasi_istirahat_1' => 20,
            'setelah_jam_ke_1'   => 5,
            'durasi_istirahat_2' => 100,
            'setelah_jam_ke_2'   => 8,
        ]);
        $ctrl->generateSlots($req2);
    }
}
