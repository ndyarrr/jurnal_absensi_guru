<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add nomor WA satpam to wa_settings
        DB::table('wa_settings')->updateOrInsert(
            ['key' => 'wa_nomor_satpam'],
            [
                'value' => '',
                'group' => 'nomor',
                'keterangan' => 'Nomor WhatsApp Pos Satpam/Gerbang (62xxx)',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        // Add announcement template for satpam
        DB::table('wa_templates')->updateOrInsert(
            ['kode' => 'pengumuman_gerbang_satpam'],
            [
                'nama' => 'Pengumuman Gerbang - Siswa Diizinkan Keluar',
                'kategori' => 'satpam',
                'format_pesan' => "📢 *PENGUMUMAN POS GERBANG*\n\nSiswa berikut telah DIIZINKAN keluar sekolah:\n\n• Nama: {nama_siswa}\n• Kelas: {nama_kelas}\n• Kegiatan: {nama_kegiatan}\n• Jam Keluar: {jam_keluar}\n• Est. Kembali: {jam_kembali}\n• Diverifikasi oleh: {nama_piket}\n\n✅ Silakan izinkan keluar gerbang.\n— Sistem SiJurnal",
                'variabel_tersedia' => json_encode(['{nama_siswa}', '{nama_kelas}', '{nama_kegiatan}', '{jam_keluar}', '{jam_kembali}', '{nama_piket}']),
                'is_active' => true,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('wa_settings')->where('key', 'wa_nomor_satpam')->delete();
        DB::table('wa_templates')->where('kode', 'pengumuman_gerbang_satpam')->delete();
    }
};
