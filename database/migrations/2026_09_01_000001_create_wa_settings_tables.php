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
        // 1. Pengaturan Utama WhatsApp & Reminder
        Schema::create('wa_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        // Seed default wa_settings
        DB::table('wa_settings')->insert([
            [
                'key' => 'wa_enabled',
                'value' => '1',
                'group' => 'general',
                'keterangan' => 'Aktifkan/Nonaktifkan semua notifikasi WhatsApp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'reminder_jurnal_enabled',
                'value' => '1',
                'group' => 'reminder',
                'keterangan' => 'Pengingat pengisian jurnal mengajar guru',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'reminder_before_minutes',
                'value' => '15',
                'group' => 'reminder',
                'keterangan' => 'Waktu kirim reminder sebelum jam pelajaran berakhir (menit)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'bot_phone_number',
                'value' => '',
                'group' => 'bot',
                'keterangan' => 'Nomor telepon WhatsApp terhubung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'notification_target_roles',
                'value' => json_encode(['admin', 'guru_piket', 'wali_kelas']),
                'group' => 'general',
                'keterangan' => 'Role default penerima notifikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 2. Template Pesan WhatsApp
        Schema::create('wa_templates', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('kategori')->default('umum');
            $table->text('format_pesan');
            $table->text('variabel_tersedia')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default wa_templates
        DB::table('wa_templates')->insert([
            [
                'kode' => 'reminder_jurnal',
                'nama' => 'Pengingat Jurnal Mengajar Guru',
                'kategori' => 'reminder',
                'format_pesan' => "Halo Bpk/Ibu {nama_guru},\n\nPengingat: Jam pelajaran ke-{jam_ke} ({mapel} di kelas {nama_kelas}) akan berakhir dalam {sisa_menit} menit (Pukul {waktu_selesai}).\n\nMohon untuk segera mengisi Jurnal Mengajar dan Presensi Siswa melalui sistem SiJurnal. Terima kasih! 🙏",
                'variabel_tersedia' => json_encode(['{nama_guru}', '{jam_ke}', '{mapel}', '{nama_kelas}', '{sisa_menit}', '{waktu_selesai}']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'presensi_guru_absen',
                'nama' => 'Notifikasi Guru Tidak Hadir',
                'kategori' => 'presensi',
                'format_pesan' => "📌 *LAPORAN INVENTARIS PIKET*\n\nBpk/Ibu Guru: {nama_guru}\nStatus: {status}\nKeterangan: {keterangan}\nTanggal: {tanggal}\n\nPesan otomatis dari Sistem SiJurnal.",
                'variabel_tersedia' => json_encode(['{nama_guru}', '{status}', '{keterangan}', '{tanggal}']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'izin_siswa',
                'nama' => 'Notifikasi Surat Izin Siswa',
                'kategori' => 'izin',
                'format_pesan' => "*SURAT IZIN SISWA*\n\nNama Siswa: {nama_siswa}\nKelas: {nama_kelas}\nJenis Izin: {jenis_izin}\nAlasan / Keterangan: {alasan}\nBerlaku Jam Ke: {jam_ke}\nGuru Piket Bertugas: {nama_piket}\n\nInfo terverifikasi oleh Guru Piket melalui Sistem SiJurnal.",
                'variabel_tersedia' => json_encode(['{nama_siswa}', '{nama_kelas}', '{jenis_izin}', '{alasan}', '{jam_ke}', '{nama_piket}']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'dispensasi_siswa',
                'nama' => 'Notifikasi Surat Dispensasi Siswa (Tugas/Kegiatan)',
                'kategori' => 'dispensasi',
                'format_pesan' => "*SURAT DISPENSASI SISWA*\n\nNama Siswa: {nama_siswa}\nKelas: {nama_kelas}\nNama Kegiatan / Tugas: {nama_kegiatan}\nLokasi / Penyelenggara: {lokasi}\nBerlaku Jam Ke: {jam_ke}\nGuru Piket Bertugas: {nama_piket}\n\nInfo terverifikasi oleh Guru Piket melalui Sistem SiJurnal.",
                'variabel_tersedia' => json_encode(['{nama_siswa}', '{nama_kelas}', '{nama_kegiatan}', '{lokasi}', '{jam_ke}', '{nama_piket}']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'izin_guru',
                'nama' => 'Notifikasi Surat Izin Guru',
                'kategori' => 'izin',
                'format_pesan' => "*PERMOHONAN / SURAT IZIN GURU*\n\nNama Guru: {nama_guru}\nJenis Izin: {jenis_izin}\nTanggal / Durasi: {tanggal}\nAlasan / Keterangan: {keterangan}\nStatus Approval: {status}\n\nCatatan terverifikasi dari Sistem SiJurnal.",
                'variabel_tersedia' => json_encode(['{nama_guru}', '{jenis_izin}', '{tanggal}', '{keterangan}', '{status}']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 3. Daftar Penerima Khusus / Notifikasi Target
        Schema::create('wa_recipients', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nomor_wa');
            $table->string('peran')->default('Admin');
            $table->boolean('terima_notifikasi')->default(true);
            $table->string('catatan')->nullable();
            $table->timestamps();
        });

        // Seed default recipient
        DB::table('wa_recipients')->insert([
            [
                'nama' => 'Administrator Utama',
                'nomor_wa' => '6281234567890',
                'peran' => 'Admin',
                'terima_notifikasi' => true,
                'catatan' => 'Penerima default log sistem WA',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wa_recipients');
        Schema::dropIfExists('wa_templates');
        Schema::dropIfExists('wa_settings');
    }
};
