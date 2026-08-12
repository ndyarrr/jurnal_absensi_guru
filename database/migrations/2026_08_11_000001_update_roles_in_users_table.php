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
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'guru_mengajar', 'guru_piket', 'wali_kelas', 'kepala_sekolah', 'waka', 'waka_sdm', 'satpam') NOT NULL DEFAULT 'guru_mengajar'");
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('guru_mengajar')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('guru_mengajar', 'wali_kelas', 'guru_piket', 'admin') NOT NULL DEFAULT 'guru_mengajar'");
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('guru_mengajar')->change();
            });
        }
    }
};
