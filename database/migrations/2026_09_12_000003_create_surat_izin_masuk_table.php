<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_izin_masuk', function (Blueprint $table) {
            $table->id('id_surat_izin_masuk');
            $table->string('nomor_surat')->unique();
            $table->integer('id_siswa')->nullable();
            $table->integer('id_kelas')->nullable();
            $table->string('nama_siswa');
            $table->string('kelas_str')->nullable();
            $table->string('jam_pelajaran_ke')->default('ke- 1');
            $table->text('alasan');
            $table->string('jenis_surat')->default('SURAT IJIN MASUK KELAS / MENINGGALKAN KELAS');
            $table->date('tanggal');
            $table->string('nama_piket_wakasek')->nullable();
            $table->string('nama_guru_piket')->nullable();
            $table->unsignedBigInteger('id_user_piket')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_izin_masuk');
    }
};
