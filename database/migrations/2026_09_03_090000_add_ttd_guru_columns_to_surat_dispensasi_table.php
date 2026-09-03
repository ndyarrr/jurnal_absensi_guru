<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('surat_dispensasi', function (Blueprint $table) {
            $table->string('ttd_guru_path')->nullable()->after('ttd_siswa_signed_name');
            $table->timestamp('ttd_guru_signed_at')->nullable()->after('ttd_guru_path');
            $table->string('ttd_guru_signed_name')->nullable()->after('ttd_guru_signed_at');
        });
    }

    public function down(): void
    {
        Schema::table('surat_dispensasi', function (Blueprint $table) {
            $table->dropColumn(['ttd_guru_path', 'ttd_guru_signed_at', 'ttd_guru_signed_name']);
        });
    }
};
