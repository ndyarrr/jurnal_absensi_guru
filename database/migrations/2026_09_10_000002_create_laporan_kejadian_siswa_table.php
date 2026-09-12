<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_kejadian_siswa', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->Integer('id_siswa');
            $table->Integer('id_kelas')->nullable();
            $table->enum('jenis_kejadian', ['terlambat_kembali', 'keluar_tanpa_izin', 'pelanggaran_tata_tertib', 'lainnya']);
            $table->text('catatan_kejadian');
            $table->unsignedBigInteger('id_user_pelapor');
            $table->boolean('kirim_ke_wali_kelas')->default(true);
            $table->boolean('kirim_ke_guru_piket')->default(true);
            $table->boolean('terkirim_wa')->default(false);
            $table->enum('status', ['draft', 'terkirim'])->default('terkirim');
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
            $table->foreign('id_user_pelapor')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_kejadian_siswa');
    }
};