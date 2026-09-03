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
        if (!Schema::hasTable('jadwal_piket')) {
            Schema::create('jadwal_piket', function (Blueprint $table) {
                $table->integer('id_piket')->autoIncrement();
                $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']);
                $table->integer('id_guru');
                $table->string('keterangan')->nullable();
                $table->timestamps();

                $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_piket');
    }
};
