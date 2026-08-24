<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengaturan_jam_sekolah', function (Blueprint $table) {
            $table->integer('durasi_istirahat_1')->nullable()->after('jam_pulang');
            $table->integer('setelah_jam_ke_1')->nullable()->after('durasi_istirahat_1');
            $table->integer('durasi_istirahat_2')->nullable()->after('setelah_jam_ke_1');
            $table->integer('setelah_jam_ke_2')->nullable()->after('durasi_istirahat_2');
        });

        // Set default break values for existing records
        DB::table('pengaturan_jam_sekolah')->where('hari_kategori', 'Senin-Kamis')->update([
            'durasi_istirahat_1' => 20,
            'setelah_jam_ke_1'   => 4,
            'durasi_istirahat_2' => 30,
            'setelah_jam_ke_2'   => 7,
        ]);

        DB::table('pengaturan_jam_sekolah')->where('hari_kategori', 'Jumat')->update([
            'durasi_istirahat_1' => 15,
            'setelah_jam_ke_1'   => 3,
            'durasi_istirahat_2' => 0,
            'setelah_jam_ke_2'   => null,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturan_jam_sekolah', function (Blueprint $table) {
            $table->dropColumn([
                'durasi_istirahat_1',
                'setelah_jam_ke_1',
                'durasi_istirahat_2',
                'setelah_jam_ke_2',
            ]);
        });
    }
};
