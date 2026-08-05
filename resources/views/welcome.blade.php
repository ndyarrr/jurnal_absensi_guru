@extends('layouts.app')

@section('title', 'Dashboard - Jurnal Absensi Guru')

@section('content')
<div class="card-panel" style="margin-bottom: 24px; text-align: center; padding: 40px 20px; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
    <div style="font-size: 3rem; margin-bottom: 10px;">🏫</div>
    <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--primary); margin-bottom: 8px;">
        Sistem Jurnal & Absensi Guru
    </h1>
    <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto; font-size: 0.95rem;">
        Kelola data jam mengajar, absensi siswa, jurnal harian, dan data master sekolah secara terstruktur dan efisien.
    </p>
</div>

<div class="form-grid">
    <div class="col-4">
        <div class="card-panel" style="padding: 24px; text-align: center;">
            <div style="font-size: 2.2rem; margin-bottom: 8px;"></div>
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 6px;">Jurnal Mengajar</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 16px;">Catat kehadiran guru, materi, & absensi siswa.</p>
            <a href="{{ route('jurnal.index') }}" class="btn btn-primary" style="width: 100%;">Buka Jurnal</a>
        </div>
    </div>

    <div class="col-4">
        <div class="card-panel" style="padding: 24px; text-align: center;">
            <div style="font-size: 2.2rem; margin-bottom: 8px;"></div>
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 6px;">Jadwal Pelajaran</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 16px;">Atur alokasi jam mengajar guru & kelas.</p>
            <a href="{{ route('jadwal.index') }}" class="btn btn-primary" style="width: 100%;">Kelola Jadwal</a>
        </div>
    </div>

    <div class="col-4">
        <div class="card-panel" style="padding: 24px; text-align: center;">
            <div style="font-size: 2.2rem; margin-bottom: 8px;"></div>
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 6px;">Data Guru</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 16px;">Manajemen data NUPTK & pengajar.</p>
            <a href="{{ route('guru.index') }}" class="btn btn-secondary" style="width: 100%;">Data Guru</a>
        </div>
    </div>

    <div class="col-4">
        <div class="card-panel" style="padding: 24px; text-align: center;">
            <div style="font-size: 2.2rem; margin-bottom: 8px;"></div>
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 6px;">Data Siswa</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 16px;">Manajemen data siswa per kelas.</p>
            <a href="{{ route('siswa.index') }}" class="btn btn-secondary" style="width: 100%;">Data Siswa</a>
        </div>
    </div>

    <div class="col-4">
        <div class="card-panel" style="padding: 24px; text-align: center;">
            <div style="font-size: 2.2rem; margin-bottom: 8px;">🏫</div>
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 6px;">Data Kelas</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 16px;">Rombongan belajar & wali kelas.</p>
            <a href="{{ route('kelas.index') }}" class="btn btn-secondary" style="width: 100%;">Data Kelas</a>
        </div>
    </div>

    <div class="col-4">
        <div class="card-panel" style="padding: 24px; text-align: center;">
            <div style="font-size: 2.2rem; margin-bottom: 8px;">🎓</div>
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 6px;">Data Jurusan</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 16px;">Kode & program keahlian sekolah.</p>
            <a href="{{ route('jurusan.index') }}" class="btn btn-secondary" style="width: 100%;">Data Jurusan</a>
        </div>
    </div>
</div>
@endsection
