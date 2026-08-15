<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\JadwalPelajaranController;
use App\Http\Controllers\JurnalMengajarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\EnsureUserIsAdmin;

// Public / Guest Auth Routes
Route::get('/', [AuthController::class, 'showLoginForm']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // Dedicated coming-soon dashboard for non-admin roles
    Route::get('/role-dashboard', [DashboardController::class, 'roleDashboard'])->name('role.dashboard');

    // Admin Only Protected Routes
    Route::middleware([EnsureUserIsAdmin::class])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('guru', GuruController::class);
        Route::resource('jurusan', JurusanController::class);
        Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);
        Route::resource('mapel', MapelController::class);
        Route::resource('siswa', SiswaController::class);
        Route::resource('jadwal', JadwalPelajaranController::class);
        Route::post('/jadwal/{jadwal}/move', [JadwalPelajaranController::class, 'move'])->name('jadwal.move');
        Route::resource('jurnal', JurnalMengajarController::class);
        Route::resource('users', UserController::class);
        Route::get('/pengguna', [UserController::class, 'index'])->name('pengguna.index');

        // Jam Pelajaran & Pengaturan Jam Sekolah Routes
        Route::get('/jam', [\App\Http\Controllers\JamPelajaranController::class, 'index'])->name('jam.index');
        Route::post('/jam/settings', [\App\Http\Controllers\JamPelajaranController::class, 'updateSettings'])->name('jam.settings');
        Route::post('/jam/generate', [\App\Http\Controllers\JamPelajaranController::class, 'generateSlots'])->name('jam.generate');
        Route::post('/jam/reorder', [\App\Http\Controllers\JamPelajaranController::class, 'reorderSlots'])->name('jam.reorder');
        Route::resource('jam-pelajaran', \App\Http\Controllers\JamPelajaranController::class)->except(['index']);
    });

});