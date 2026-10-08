<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_dispensasi_siswa', function (Blueprint $table) {
            $table->id();

            $table->integer('id_dispen');
            $table->integer('id_siswa');

            $table->foreign('id_dispen')
                ->references('id_dispen')
                ->on('surat_dispensasi')
                ->onDelete('cascade');

            $table->foreign('id_siswa')
                ->references('id_siswa')
                ->on('siswa')
                ->onDelete('cascade');

            $table->unique(['id_dispen', 'id_siswa']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_dispensasi_siswa');
    }
};