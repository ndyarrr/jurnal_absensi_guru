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
        Schema::table('jam_pelajaran', function (Blueprint $table) {
            $table->string('hari_kategori')->default('Senin-Kamis')->after('id_jam'); // 'Senin-Kamis', 'Jumat', 'Sabtu'
            $table->boolean('is_istirahat')->default(false)->after('jam_selesai');
            $table->integer('durasi_menit')->nullable()->after('is_istirahat');
        });

        // Remove old unique constraint on jam_ke so same jam_ke can exist for different hari_kategori
        try {
            Schema::table('jam_pelajaran', function (Blueprint $table) {
                $table->dropUnique('jam_pelajaran_jam_ke_unique');
            });
        } catch (\Exception $e) {
            // Drop unique index if exists
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jam_pelajaran', function (Blueprint $table) {
            $table->dropColumn(['hari_kategori', 'is_istirahat', 'durasi_menit']);
        });
    }
};
