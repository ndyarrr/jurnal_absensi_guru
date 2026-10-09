<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_izin_masuk', function (Blueprint $table) {
            // Tanda tangan digital siswa
            $table->string('ttd_siswa_path')->nullable()->after('id_user_piket');
            $table->timestamp('ttd_siswa_signed_at')->nullable()->after('ttd_siswa_path');
            $table->string('ttd_siswa_signed_name')->nullable()->after('ttd_siswa_signed_at');

            // Tanda tangan digital guru piket (pembuat surat)
            $table->string('ttd_guru_piket_path')->nullable()->after('ttd_siswa_signed_name');
            $table->timestamp('ttd_guru_piket_signed_at')->nullable()->after('ttd_guru_piket_path');
            $table->string('ttd_guru_piket_signed_name')->nullable()->after('ttd_guru_piket_signed_at');

            // Tanda tangan digital piket wakasek (ditandatangani akun piket wakasek sendiri)
            $table->string('ttd_wakasek_path')->nullable()->after('ttd_guru_piket_signed_name');
            $table->timestamp('ttd_wakasek_signed_at')->nullable()->after('ttd_wakasek_path');
            $table->string('ttd_wakasek_signed_name')->nullable()->after('ttd_wakasek_signed_at');
            $table->unsignedBigInteger('ttd_wakasek_id_user')->nullable()->after('ttd_wakasek_signed_name');
        });
    }

    public function down(): void
    {
        Schema::table('surat_izin_masuk', function (Blueprint $table) {
            $table->dropColumn([
                'ttd_siswa_path', 'ttd_siswa_signed_at', 'ttd_siswa_signed_name',
                'ttd_guru_piket_path', 'ttd_guru_piket_signed_at', 'ttd_guru_piket_signed_name',
                'ttd_wakasek_path', 'ttd_wakasek_signed_at', 'ttd_wakasek_signed_name', 'ttd_wakasek_id_user',
            ]);
        });
    }
};