<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengaturan_jam_sekolah', function (Blueprint $table) {
            $table->string('mode_durasi_kbm')->default('seragam')->after('durasi_per_jam');
            $table->integer('durasi_jam_utama')->nullable()->after('mode_durasi_kbm');
            $table->integer('sampai_jam_ke')->nullable()->after('durasi_jam_utama');
            $table->integer('durasi_jam_setelahnya')->nullable()->after('sampai_jam_ke');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturan_jam_sekolah', function (Blueprint $table) {
            $table->dropColumn([
                'mode_durasi_kbm',
                'durasi_jam_utama',
                'sampai_jam_ke',
                'durasi_jam_setelahnya',
            ]);
        });
    }
};
