<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengubah ENUM kategori_izin agar sesuai dengan form Pengajuan Izin Guru Mengajar.
     * Nilai lama: sakit, dinas_luar, urusan_keluarga, pelatihan, lainnya
     * Nilai baru: sakit, dinas, cuti, acara_keluarga, lainnya
     */
    public function up(): void
    {
        // Migrasi nilai lama ke nilai baru sebelum mengubah tipe kolom
        DB::table('izin_guru')->where('kategori_izin', 'dinas_luar')->update(['kategori_izin' => 'dinas']);
        DB::table('izin_guru')->where('kategori_izin', 'urusan_keluarga')->update(['kategori_izin' => 'acara_keluarga']);
        DB::table('izin_guru')->where('kategori_izin', 'pelatihan')->update(['kategori_izin' => 'dinas']);

        DB::statement("ALTER TABLE izin_guru MODIFY COLUMN kategori_izin ENUM('sakit','dinas','cuti','acara_keluarga','lainnya') NOT NULL DEFAULT 'sakit'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('izin_guru')->where('kategori_izin', 'dinas')->update(['kategori_izin' => 'dinas_luar']);
        DB::table('izin_guru')->where('kategori_izin', 'acara_keluarga')->update(['kategori_izin' => 'urusan_keluarga']);
        DB::table('izin_guru')->where('kategori_izin', 'cuti')->update(['kategori_izin' => 'lainnya']);

        DB::statement("ALTER TABLE izin_guru MODIFY COLUMN kategori_izin ENUM('sakit','dinas_luar','urusan_keluarga','pelatihan','lainnya') NOT NULL DEFAULT 'sakit'");
    }
};
