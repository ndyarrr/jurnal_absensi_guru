<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Expand ENUM to temporarily contain both waka_sdm and waka_kurikulum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'super_admin', 'guru_mengajar', 'guru_piket', 'wali_kelas', 'kepala_sekolah', 'waka', 'waka_sdm', 'waka_kurikulum', 'satpam') NOT NULL DEFAULT 'guru_mengajar'");

        // 2. Update existing waka_sdm records to waka_kurikulum
        DB::table('users')->where('role', 'waka_sdm')->update(['role' => 'waka_kurikulum']);

        // 3. Narrow ENUM to remove waka_sdm
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'super_admin', 'guru_mengajar', 'guru_piket', 'wali_kelas', 'kepala_sekolah', 'waka', 'waka_kurikulum', 'satpam') NOT NULL DEFAULT 'guru_mengajar'");

        // 4. Add Waka Kurikulum approval columns to izin_guru
        Schema::table('izin_guru', function (Blueprint $table) {
            if (!Schema::hasColumn('izin_guru', 'status_waka_kurikulum')) {
                $table->enum('status_waka_kurikulum', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('tgl_disetujui_waka');
                $table->unsignedBigInteger('disetujui_waka_kurikulum_oleh')->nullable()->after('status_waka_kurikulum');
                $table->timestamp('tgl_disetujui_waka_kurikulum')->nullable()->after('disetujui_waka_kurikulum_oleh');
            }
        });

        // 5. Add Waka Kurikulum approval columns to surat_dispensasi
        Schema::table('surat_dispensasi', function (Blueprint $table) {
            if (!Schema::hasColumn('surat_dispensasi', 'status_waka_kurikulum')) {
                $table->enum('status_waka_kurikulum', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('tgl_disetujui_waka');
                $table->unsignedBigInteger('disetujui_waka_kurikulum_oleh')->nullable()->after('status_waka_kurikulum');
                $table->timestamp('tgl_disetujui_waka_kurikulum')->nullable()->after('disetujui_waka_kurikulum_oleh');
            }
        });
    }

    public function down(): void
    {
        Schema::table('izin_guru', function (Blueprint $table) {
            if (Schema::hasColumn('izin_guru', 'status_waka_kurikulum')) {
                $table->dropColumn(['status_waka_kurikulum', 'disetujui_waka_kurikulum_oleh', 'tgl_disetujui_waka_kurikulum']);
            }
        });

        Schema::table('surat_dispensasi', function (Blueprint $table) {
            if (Schema::hasColumn('surat_dispensasi', 'status_waka_kurikulum')) {
                $table->dropColumn(['status_waka_kurikulum', 'disetujui_waka_kurikulum_oleh', 'tgl_disetujui_waka_kurikulum']);
            }
        });

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'super_admin', 'guru_mengajar', 'guru_piket', 'wali_kelas', 'kepala_sekolah', 'waka', 'waka_sdm', 'waka_kurikulum', 'satpam') NOT NULL DEFAULT 'guru_mengajar'");
        DB::table('users')->where('role', 'waka_kurikulum')->update(['role' => 'waka_sdm']);
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'super_admin', 'guru_mengajar', 'guru_piket', 'wali_kelas', 'kepala_sekolah', 'waka', 'waka_sdm', 'satpam') NOT NULL DEFAULT 'guru_mengajar'");
    }
};
