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
            $table->string('mode_istirahat_1')->default('durasi')->after('setelah_jam_ke_1');
            $table->time('jam_mulai_istirahat_1')->nullable()->after('mode_istirahat_1');
            $table->time('jam_selesai_istirahat_1')->nullable()->after('jam_mulai_istirahat_1');

            $table->string('mode_istirahat_2')->default('durasi')->after('setelah_jam_ke_2');
            $table->time('jam_mulai_istirahat_2')->nullable()->after('mode_istirahat_2');
            $table->time('jam_selesai_istirahat_2')->nullable()->after('jam_mulai_istirahat_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturan_jam_sekolah', function (Blueprint $table) {
            $table->dropColumn([
                'mode_istirahat_1',
                'jam_mulai_istirahat_1',
                'jam_selesai_istirahat_1',
                'mode_istirahat_2',
                'jam_mulai_istirahat_2',
                'jam_selesai_istirahat_2',
            ]);
        });
    }
};
