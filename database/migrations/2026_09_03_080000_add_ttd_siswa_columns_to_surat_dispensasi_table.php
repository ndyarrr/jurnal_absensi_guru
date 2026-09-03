<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('surat_dispensasi', function (Blueprint $table) {
            $table->string('ttd_siswa_path')->nullable()->after('barcode_token');
            $table->timestamp('ttd_siswa_signed_at')->nullable()->after('ttd_siswa_path');
            $table->string('ttd_siswa_signed_name')->nullable()->after('ttd_siswa_signed_at');
        });
    }

    public function down(): void
    {
        Schema::table('surat_dispensasi', function (Blueprint $table) {
            $table->dropColumn(['ttd_siswa_path', 'ttd_siswa_signed_at', 'ttd_siswa_signed_name']);
        });
    }
};
