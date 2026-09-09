<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom username setelah kolom name
            // nullable dulu agar bisa di-fill sebelum unique constraint
            $table->string('username')->nullable()->after('name');
        });

        // Isi username dari NIP guru yang sudah ada
        // Non-guru (admin, kepsek, dll) username = name mereka
        \DB::statement("
            UPDATE users u
            LEFT JOIN guru g ON u.id_guru = g.id_guru
            SET u.username = CASE
                WHEN g.nip IS NOT NULL AND g.nip != '' THEN g.nip
                ELSE u.name
            END
        ");

        Schema::table('users', function (Blueprint $table) {
            // Setelah terisi semua, buat unique + not null
            $table->string('username')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
