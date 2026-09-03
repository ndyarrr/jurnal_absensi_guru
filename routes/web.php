<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\JadwalPelajaranController;
use App\Http\Controllers\JadwalPiketController;
use App\Http\Controllers\JurnalMengajarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PanduanAdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuanganController;
use App\Http\Middleware\EnsureUserIsAdmin;

// Public / Guest Auth Routes
Route::get('/', [AuthController::class, 'showLoginForm']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // Profile Settings Route for all authenticated users
    Route::put('/profil', [ProfileController::class, 'update'])->name('profil.update');

    // Dedicated coming-soon dashboard for non-admin roles
    Route::get('/role-dashboard', [DashboardController::class, 'roleDashboard'])->name('role.dashboard');
    Route::get('/guru/dashboard', [DashboardController::class, 'guruMengajarDashboard'])->name('guru-mengajar.dashboard');
    Route::get('/wali-kelas/dashboard', [\App\Http\Controllers\WaliKelasController::class, 'dashboard'])->name('wali-kelas.dashboard');
    Route::get('/wali-kelas/perwalian', [\App\Http\Controllers\WaliKelasController::class, 'perwalian'])->name('wali-kelas.perwalian');
    Route::get('/wali-kelas/rekap-kehadiran', [\App\Http\Controllers\WaliKelasController::class, 'rekapKehadiran'])->name('wali-kelas.rekap-kehadiran');
    Route::get('/wali-kelas/surat-izin', [\App\Http\Controllers\WaliKelasController::class, 'suratIzin'])->name('wali-kelas.surat-izin');
    Route::get('/wali-kelas/rekap-kehadiran/export/csv', [\App\Http\Controllers\WaliKelasController::class, 'exportRekapCsv'])->name('wali-kelas.rekap-kehadiran.export-csv');

    // Dedicated Routes for Guru Mengajar
    Route::get('/guru-mengajar/dashboard', [\App\Http\Controllers\GuruMengajarController::class, 'dashboard'])->name('guru-mengajar.dashboard');
    Route::get('/guru-mengajar/jadwal', [\App\Http\Controllers\GuruMengajarController::class, 'jadwal'])->name('guru-mengajar.jadwal');
    Route::get('/guru-mengajar/jurnal', [\App\Http\Controllers\GuruMengajarController::class, 'jurnal'])->name('guru-mengajar.jurnal');
    Route::post('/guru-mengajar/jurnal', [\App\Http\Controllers\GuruMengajarController::class, 'storeJurnal'])->name('guru-mengajar.jurnal.store');
    Route::get('/guru-mengajar/jurnal/export/csv', [\App\Http\Controllers\GuruMengajarController::class, 'exportCsv'])->name('guru-mengajar.export-csv');
    Route::get('/guru-mengajar/jadwal/{idJadwal}/siswa', [\App\Http\Controllers\GuruMengajarController::class, 'getSiswaForJadwal'])->name('guru-mengajar.jadwal.siswa');
    Route::get('/guru-mengajar/absensi', [\App\Http\Controllers\GuruMengajarController::class, 'absensi'])->name('guru-mengajar.absensi');
    Route::get('/guru-mengajar/nilai', [\App\Http\Controllers\GuruMengajarController::class, 'nilai'])->name('guru-mengajar.nilai');

    // Dedicated Routes for Guru Piket
    Route::get('/guru-piket/dashboard', [\App\Http\Controllers\GuruPiketController::class, 'dashboard'])->name('guru-piket.dashboard');
    Route::get('/guru-piket/input-surat', [\App\Http\Controllers\GuruPiketController::class, 'inputSuratIzin'])->name('guru-piket.input-surat');
    Route::post('/guru-piket/input-surat', [\App\Http\Controllers\GuruPiketController::class, 'storeSuratIzin'])->name('guru-piket.store-surat');
    Route::get('/guru-piket/input-dispensasi', [\App\Http\Controllers\GuruPiketController::class, 'inputDispensasi'])->name('guru-piket.input-dispensasi');
    Route::post('/guru-piket/input-dispensasi', [\App\Http\Controllers\GuruPiketController::class, 'storeDispensasi'])->name('guru-piket.store-dispensasi');
    Route::get('/guru-piket/digital-surat', [\App\Http\Controllers\GuruPiketController::class, 'digitalisasiSurat'])->name('guru-piket.digital-surat');
    Route::get('/guru-piket/export/csv', [\App\Http\Controllers\GuruPiketController::class, 'exportCsv'])->name('guru-piket.export-csv');
    Route::post('/guru-piket/dispensasi/{id}/ttd-siswa', [\App\Http\Controllers\GuruPiketController::class, 'simpanTtdSiswa'])->name('guru-piket.dispensasi.ttd-siswa');
    Route::post('/guru-piket/dispensasi/{id}/ttd-guru', [\App\Http\Controllers\GuruPiketController::class, 'simpanTtdGuru'])->name('guru-piket.dispensasi.ttd-guru');

    // Jurnal & Jadwal routes for all authenticated users (Guru, Admin, etc.)
    Route::get('/jadwal/export/csv', [JadwalPelajaranController::class, 'exportCsv'])->name('jadwal.export-csv');
    Route::get('/jadwal/export/pdf', [JadwalPelajaranController::class, 'exportPdf'])->name('jadwal.export-pdf');
    Route::resource('jadwal', JadwalPelajaranController::class);
    Route::post('/jadwal/{jadwal}/move', [JadwalPelajaranController::class, 'move'])->name('jadwal.move');
    Route::get('/jurnal/export/csv', [JurnalMengajarController::class, 'exportCsv'])->name('jurnal.export-csv');
    Route::resource('jurnal', JurnalMengajarController::class);

    // Admin Only Protected Routes
    Route::middleware([EnsureUserIsAdmin::class])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/export/csv', [DashboardController::class, 'exportCsv'])->name('dashboard.export-csv');
        Route::get('/panduan-admin', [PanduanAdminController::class, 'index'])->name('panduan.index');

        Route::get('/guru/export/csv', [GuruController::class, 'exportCsv'])->name('guru.export-csv');
        Route::resource('guru', GuruController::class);
        Route::resource('jurusan', JurusanController::class);
        Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);
        Route::resource('mapel', MapelController::class);
        Route::resource('ruangan', RuanganController::class);
        Route::get('/siswa/export/csv', [SiswaController::class, 'exportCsv'])->name('siswa.export-csv');
        Route::resource('siswa', SiswaController::class);
        Route::resource('jadwal-piket', JadwalPiketController::class);
        Route::resource('users', UserController::class);
        Route::get('/pengguna', [UserController::class, 'index'])->name('pengguna.index');

        // Jam Pelajaran & Pengaturan Jam Sekolah Routes
        Route::get('/jam', [\App\Http\Controllers\JamPelajaranController::class, 'index'])->name('jam.index');
        Route::post('/jam/settings', [\App\Http\Controllers\JamPelajaranController::class, 'updateSettings'])->name('jam.settings');
        Route::post('/jam/generate', [\App\Http\Controllers\JamPelajaranController::class, 'generateSlots'])->name('jam.generate');
        Route::post('/jam/reorder', [\App\Http\Controllers\JamPelajaranController::class, 'reorderSlots'])->name('jam.reorder');
        Route::resource('jam-pelajaran', \App\Http\Controllers\JamPelajaranController::class)->except(['index']);

        // Kategori Baru: Pengaturan / Notifikasi WhatsApp
        Route::prefix('pengaturan-wa')->name('pengaturan-wa.')->group(function () {
            Route::get('/', [\App\Http\Controllers\PengaturanWaController::class, 'index'])->name('index');
            Route::get('/api/status', [\App\Http\Controllers\PengaturanWaController::class, 'apiStatus'])->name('api-status');
            Route::post('/pair-code', [\App\Http\Controllers\PengaturanWaController::class, 'requestPairingCode'])->name('pair-code');
            Route::post('/logout', [\App\Http\Controllers\PengaturanWaController::class, 'logoutBot'])->name('logout');
            Route::post('/reconnect', [\App\Http\Controllers\PengaturanWaController::class, 'reconnectBot'])->name('reconnect');
            Route::post('/start', [\App\Http\Controllers\PengaturanWaController::class, 'startBot'])->name('start');
            Route::post('/stop', [\App\Http\Controllers\PengaturanWaController::class, 'stopBot'])->name('stop');
            Route::post('/settings', [\App\Http\Controllers\PengaturanWaController::class, 'updateSettings'])->name('settings.update');
            Route::post('/test-send', [\App\Http\Controllers\PengaturanWaController::class, 'sendTestMessage'])->name('test-send');
            
            // Templates
            Route::post('/templates', [\App\Http\Controllers\PengaturanWaController::class, 'storeTemplate'])->name('templates.store');
            Route::put('/templates/{id}', [\App\Http\Controllers\PengaturanWaController::class, 'updateTemplate'])->name('templates.update');
            Route::delete('/templates/{id}', [\App\Http\Controllers\PengaturanWaController::class, 'destroyTemplate'])->name('templates.destroy');

            // Recipients
            Route::post('/recipients', [\App\Http\Controllers\PengaturanWaController::class, 'storeRecipient'])->name('recipients.store');
            Route::put('/recipients/{id}', [\App\Http\Controllers\PengaturanWaController::class, 'updateRecipient'])->name('recipients.update');
            Route::delete('/recipients/{id}', [\App\Http\Controllers\PengaturanWaController::class, 'destroyRecipient'])->name('recipients.destroy');
        });
    });

});