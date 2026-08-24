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
        Schema::create('izin_guru', function (Blueprint $table) {
            $table->integer('id_izin_guru')->autoIncrement();
            $table->integer('id_guru');
            $table->enum('kategori_izin', [
                'sakit',
                'dinas_luar',
                'urusan_keluarga',
                'pelatihan',
                'lainnya'
            ])->default('sakit');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->text('alasan_izin');
            $table->string('bukti_surat')->nullable();
            $table->enum('status_approval', [
                'pending',
                'disetujui_piket',
                'disetujui_waka',
                'disetujui_kepsek',
                'ditolak'
            ])->default('pending');
            $table->bigInteger('disetujui_oleh')->unsigned()->nullable();
            $table->text('catatan_approver')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
            $table->foreign('disetujui_oleh')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_guru');
    }
};
