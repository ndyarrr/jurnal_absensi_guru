<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\JadwalPelajaranController;
use App\Http\Controllers\JurnalMengajarController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('guru', GuruController::class);
Route::resource('jurusan', JurusanController::class);
Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);
Route::resource('mapel', MapelController::class);
Route::resource('siswa', SiswaController::class);
Route::resource('jadwal', JadwalPelajaranController::class);
Route::resource('jurnal', JurnalMengajarController::class);
Route::resource('users', UserController::class);