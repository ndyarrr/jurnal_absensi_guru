<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan role 'orang_tua' ke kolom users.role (MySQL memakai ENUM).
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'super_admin', 'guru_mengajar', 'guru_piket', 'wali_kelas', 'kepala_sekolah', 'waka', 'waka_kurikulum', 'satpam', 'orang_tua') NOT NULL DEFAULT 'guru_mengajar'");
        }

        // 2. Tabel penghubung akun orang tua <-> anak (siswa). Satu akun bisa punya banyak anak,
        //    satu siswa juga bisa dihubungkan ke beberapa akun (ayah, ibu, wali).
        Schema::create('orang_tua_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->integer('id_siswa');
            $table->enum('hubungan', ['ayah', 'ibu', 'wali'])->default('wali');
            $table->timestamps();

            $table->unique(['id_user', 'id_siswa']);
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orang_tua_siswa');

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::table('users')->where('role', 'orang_tua')->delete();
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'super_admin', 'guru_mengajar', 'guru_piket', 'wali_kelas', 'kepala_sekolah', 'waka', 'waka_kurikulum', 'satpam') NOT NULL DEFAULT 'guru_mengajar'");
        }
    }
};