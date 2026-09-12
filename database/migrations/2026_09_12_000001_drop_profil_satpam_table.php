<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('profil_satpam');
    }

    public function down(): void
    {
        Schema::create('profil_satpam', function (Blueprint $table) {
            $table->id('id_profil_satpam');
            $table->unsignedBigInteger('id_user')->unique();
            $table->string('id_petugas', 50)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('pos_jaga', 100)->nullable();
            $table->string('jadwal_shift', 100)->nullable();
            $table->boolean('notif_izin_belum_kembali')->default(true);
            $table->boolean('notif_laporan_terkirim')->default(true);
            $table->boolean('notif_pengumuman_sekolah')->default(false);
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }
};