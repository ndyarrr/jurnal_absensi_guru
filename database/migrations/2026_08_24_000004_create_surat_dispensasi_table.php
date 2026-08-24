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
        Schema::create('surat_dispensasi', function (Blueprint $table) {
            $table->integer('id_dispen')->autoIncrement();
            $table->string('nomor_surat', 100)->unique();
            $table->enum('tipe_pemohon', ['siswa', 'guru'])->default('siswa');
            $table->integer('id_siswa')->nullable();
            $table->integer('id_guru')->nullable();
            $table->integer('id_kelas')->nullable();
            $table->string('nama_kegiatan', 255);
            $table->string('lokasi_kegiatan', 255)->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->text('alasan_dispensasi');
            $table->string('file_surat')->nullable();
            $table->enum('status_approval', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->bigInteger('disetujui_oleh')->unsigned()->nullable();
            $table->string('barcode_token', 100)->unique()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
            $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('cascade');
            $table->foreign('disetujui_oleh')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_dispensasi');
    }
};
