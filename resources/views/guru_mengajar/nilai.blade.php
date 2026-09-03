@extends('layouts.guru_mengajar')

@section('title', 'Nilai & Rapor')
@section('page-title', 'Nilai & Rapor')
@section('page-subtitle', 'Fitur input nilai & rapor siswa')

@section('content')

    <div class="gm-coming-soon">
        <div class="gm-coming-soon-icon"><i class="fa-solid fa-file-invoice"></i></div>
        <h2 style="font-size: 1.3rem; font-weight: 800; color: var(--dash-text-dark);">Modul Nilai & Rapor Segera Hadir</h2>
        <p style="max-width: 460px; color: var(--dash-text-muted); font-size: 0.9rem; line-height: 1.6;">
            Fitur input nilai dan cetak rapor siswa membutuhkan tabel database baru (nilai, komponen penilaian, dsb.)
            yang belum tersedia di skema saat ini. Halaman ini akan aktif setelah struktur tabel nilai dirancang
            dan disepakati bersama tim.
        </p>
        <span class="gm-pill" style="background: var(--dash-cream-light); border-color: var(--dash-cream-border); color: var(--dash-text-dark);">
            <i class="fa-solid fa-circle-info"></i> Diskusikan struktur tabel nilai dengan tim sebelum implementasi
        </span>
    </div>

@endsection