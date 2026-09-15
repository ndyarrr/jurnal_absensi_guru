<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $kelasKosong = DB::table('kelas')
            ->whereNull('id_guru_wali')
            ->whereNotNull('wali_kelas')
            ->get();

        foreach ($kelasKosong as $kelas) {
            $guru = DB::table('guru')->where('nama_guru', $kelas->wali_kelas)->first();

            if ($guru) {
                DB::table('kelas')
                    ->where('id_kelas', $kelas->id_kelas)
                    ->update(['id_guru_wali' => $guru->id_guru]);
            }
        }
    }

    public function down(): void
    {
        // Nggak perlu reverse - ini cuma nyinkronin data, aman dibiarkan begitu aja
    }
};