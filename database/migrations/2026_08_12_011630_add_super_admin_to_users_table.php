<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'super_admin', 'guru_mengajar', 'guru_piket', 'wali_kelas', 'kepala_sekolah', 'waka', 'waka_sdm', 'satpam') NOT NULL DEFAULT 'guru_mengajar'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'guru_mengajar', 'guru_piket', 'wali_kelas', 'kepala_sekolah', 'waka', 'waka_sdm', 'satpam') NOT NULL DEFAULT 'guru_mengajar'");
        }
    }
};
