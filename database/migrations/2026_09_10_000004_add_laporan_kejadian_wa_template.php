<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('wa_templates')->updateOrInsert(
            ['kode' => 'laporan_kejadian_siswa'],
            [
                'nama' => 'Laporan Kejadian Siswa dari Satpam',
                'kategori' => 'laporan',
                'format_pesan' => "*LAPORAN KEJADIAN SISWA*\n\nSiswa: {nama_siswa}\nKelas: {nama_kelas}\nJenis Kejadian: {jenis_kejadian}\n\nKeterangan:\n{catatan_kejadian}\n\nDilaporkan oleh: {nama_pelapor}\nWaktu: {tanggal}\n\nInfo otomatis dari Sistem SiJurnal.",
                'variabel_tersedia' => json_encode(['{nama_siswa}', '{nama_kelas}', '{jenis_kejadian}', '{catatan_kejadian}', '{nama_pelapor}', '{tanggal}']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('wa_templates')->where('kode', 'laporan_kejadian_siswa')->delete();
    }
};