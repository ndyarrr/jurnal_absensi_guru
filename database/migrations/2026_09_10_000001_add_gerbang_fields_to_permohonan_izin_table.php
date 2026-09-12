<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permohonan_izin', function (Blueprint $table) {
            $table->time('jam_keluar')->nullable()->after('bukti_surat');
            $table->time('perkiraan_kembali')->nullable()->after('jam_keluar');
            $table->time('jam_kembali_aktual')->nullable()->after('perkiraan_kembali');
            $table->unsignedBigInteger('dicatat_oleh_user_id')->nullable()->after('jam_kembali_aktual');
        });
    }

    public function down(): void
    {
        Schema::table('permohonan_izin', function (Blueprint $table) {
            $table->dropColumn(['jam_keluar', 'perkiraan_kembali', 'jam_kembali_aktual', 'dicatat_oleh_user_id']);
        });
    }
};