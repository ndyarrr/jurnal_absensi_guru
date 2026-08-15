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
        Schema::create('pengaturan_jam_sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('hari_kategori')->unique(); // 'Senin-Kamis', 'Jumat'
            $table->integer('durasi_per_jam')->default(40); // durasi 1 jam pelajaran dalam menit
            $table->time('jam_masuk')->default('07:00:00');
            $table->time('jam_pulang')->default('14:30:00');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        // Insert default initial records
        DB::table('pengaturan_jam_sekolah')->insert([
            [
                'hari_kategori'  => 'Senin-Kamis',
                'durasi_per_jam' => 40,
                'jam_masuk'      => '07:00:00',
                'jam_pulang'     => '14:30:00',
                'keterangan'     => 'Hari Reguler (1 Jam = 40 Menit)',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'hari_kategori'  => 'Jumat',
                'durasi_per_jam' => 30,
                'jam_masuk'      => '07:00:00',
                'jam_pulang'     => '11:30:00',
                'keterangan'     => 'Hari Singkat / Sholat Jumat (1 Jam = 30 Menit)',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_jam_sekolah');
    }
};
