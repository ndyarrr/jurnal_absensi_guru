<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE surat_dispensasi MODIFY COLUMN status_approval ENUM('pending','disetujui_piket','disetujui_waka','disetujui','ditolak') NOT NULL DEFAULT 'pending'");
        }

        Schema::table('surat_dispensasi', function (Blueprint $table) {
            if (!Schema::hasColumn('surat_dispensasi', 'status_waka')) {
                $table->enum('status_waka', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('status_approval');
                $table->bigInteger('disetujui_waka_oleh')->unsigned()->nullable()->after('status_waka');
                $table->timestamp('tgl_disetujui_waka')->nullable()->after('disetujui_waka_oleh');
            }
            if (!Schema::hasColumn('surat_dispensasi', 'status_kepsek')) {
                $table->enum('status_kepsek', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('tgl_disetujui_waka');
                $table->bigInteger('disetujui_kepsek_oleh')->unsigned()->nullable()->after('status_kepsek');
                $table->timestamp('tgl_disetujui_kepsek')->nullable()->after('disetujui_kepsek_oleh');
            }
            if (!Schema::hasColumn('surat_dispensasi', 'catatan_approver')) {
                $table->text('catatan_approver')->nullable()->after('tgl_disetujui_kepsek');
            }
        });

        Schema::table('izin_guru', function (Blueprint $table) {
            if (!Schema::hasColumn('izin_guru', 'status_waka')) {
                $table->enum('status_waka', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('status_approval');
                $table->bigInteger('disetujui_waka_oleh')->unsigned()->nullable()->after('status_waka');
                $table->timestamp('tgl_disetujui_waka')->nullable()->after('disetujui_waka_oleh');
            }
            if (!Schema::hasColumn('izin_guru', 'status_kepsek')) {
                $table->enum('status_kepsek', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('tgl_disetujui_waka');
                $table->bigInteger('disetujui_kepsek_oleh')->unsigned()->nullable()->after('status_kepsek');
                $table->timestamp('tgl_disetujui_kepsek')->nullable()->after('disetujui_kepsek_oleh');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_dispensasi', function (Blueprint $table) {
            $table->dropColumn(['status_waka', 'disetujui_waka_oleh', 'tgl_disetujui_waka', 'status_kepsek', 'disetujui_kepsek_oleh', 'tgl_disetujui_kepsek', 'catatan_approver']);
        });

        Schema::table('izin_guru', function (Blueprint $table) {
            $table->dropColumn(['status_waka', 'disetujui_waka_oleh', 'tgl_disetujui_waka', 'status_kepsek', 'disetujui_kepsek_oleh', 'tgl_disetujui_kepsek']);
        });
    }
};
