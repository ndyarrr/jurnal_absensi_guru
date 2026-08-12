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
        Schema::create('permohonan_izin', function (Blueprint $table) {
            $table->integer('id_permohonan')->autoIncrement();
            $table->enum('tipe_pemohon', ['guru', 'siswa']);
            $table->integer('id_guru')->nullable();
            $table->integer('id_siswa')->nullable();
            $table->string('jenis_izin', 100);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('alasan');
            $table->string('bukti_surat')->nullable();
            $table->enum('status', [
                'pending',
                'approved_piket',
                'approved_waka',
                'approved_waka_sdm',
                'approved_kepsek',
                'rejected'
            ])->default('pending');
            $table->text('catatan_revisi')->nullable();
            $table->timestamps();

            $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
        });

        Schema::create('approval_logs', function (Blueprint $table) {
            $table->integer('id_approval')->autoIncrement();
            $table->integer('id_permohonan');
            $table->bigInteger('id_user_approver')->unsigned();
            $table->string('role_approver', 50);
            $table->enum('aksi', ['approved', 'rejected']);
            $table->text('catatan')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_permohonan')->references('id_permohonan')->on('permohonan_izin')->onDelete('cascade');
            $table->foreign('id_user_approver')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_logs');
        Schema::dropIfExists('permohonan_izin');
    }
};
