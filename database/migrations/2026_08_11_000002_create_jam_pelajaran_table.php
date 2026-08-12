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
        Schema::create('jam_pelajaran', function (Blueprint $table) {
            $table->integer('id_jam')->autoIncrement();
            $table->integer('jam_ke')->unique();
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::table('jadwal_pelajaran', function (Blueprint $table) {
            $table->integer('id_jam')->nullable()->after('jam_ke');
            $table->foreign('id_jam')->references('id_jam')->on('jam_pelajaran')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pelajaran', function (Blueprint $table) {
            $table->dropForeign(['id_jam']);
            $table->dropColumn('id_jam');
        });

        Schema::dropIfExists('jam_pelajaran');
    }
};
